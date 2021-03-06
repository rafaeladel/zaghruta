<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\Album;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Photo;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\Wishlist;
use Zgh\FEBundle\Form\PhotoCaptionType;
use Zgh\FEBundle\Form\WishlistType;

class PhotoController extends Controller
{
    /**
     * @ParamConverter("photo", class="ZghFEBundle:Photo", options={"id" = "photo_id"})
     */
    public function getPhotoContentAction(User $user, Photo $photo)
    {
        return $this->render("@ZghFE/Default/photo.html.twig", [
            "user" => $user,
            "photo" => $photo,
        ]);
    }

    public function getPhotoPhotosPartialContentAction(User $user)
    {
        $photos = $user->getPhotos();
        return $this->render("@ZghFE/Partial/photos/user_profile_photos_p_content.html.twig", array(
            "user" => $user,
            "photos" => $photos
        ));
    }

    public function getPhotoAlbumsPartialContentAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $albums = $user->getAlbums();
        return $this->render("@ZghFE/Partial/photos/user_profile_photos_a_content.html.twig", array(
            "albums" => $albums,
            "user" => $user
        ));
    }

    public function getPhotoAlbumsPhotosPartialContentAction($id, $album_id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $albums = $user->getAlbums();
        $album = $this->getDoctrine()->getRepository("ZghFEBundle:Album")->find($album_id);
        return $this->render("@ZghFE/Partial/photos/user_profile_album_content.html.twig", array(
            "user" => $user,
            "albums" => $albums,
            "album" => $album
        ));
    }

    public function checkAlbumUniqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get("security.context")->getToken()->getUser();
        $album_name = $request->query->get("album_name");
        $album = $em->getRepository("ZghFEBundle:User")->hasAlbum($user, $album_name);
        $already_exists = false;
        if($album) {
           $already_exists = true;
        }
        return new JsonResponse([
            "already_exists" => $already_exists
        ]);
    }

    public function postPhotoAlbumNewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get("security.context")->getToken()->getUser();
        $album_name = $request->request->get("album_name");
        $album_id = $request->request->get("album_id");
        $album_info = $request->request->get("album_info");
        $image_caption = $request->request->get("caption");
        $album = $em->getRepository("ZghFEBundle:User")->hasAlbum($user, $album_name);
        if (!$album) {
            $album = new Album();
            $album->setName($album_name);
            $album->setInfo($album_info);
            $user->addAlbum($album);
        }


        if ($request->files->count() > 0) {
            $photo = new Photo();
            $photo->setImageFile($request->files->get("file"));
            $photo->setCaption($image_caption);
            $album->addPhoto($photo);
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $url = $this->generateUrl("zgh_fe.photos_partial_albums_photos_content", [ "id" => $user->getId(), "album_id" => $album->getId() ], true);
        return new JsonResponse(array("success" => true, "url" => $url ));
    }

    public function postPhotoAlbumDeleteAction(Request $request, Album $album)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($album);
        $em->flush();
        $this->get("session")->getFlashBag()->add("notice", "Album Deleted!");
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.albums_partial", array('id' => $album->getUser()->getId())));
    }

    public function postPhotoPhotoNewAction(Request $request)
    {
//        var_dump($request->request->all());
//        die;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $album_id = $request->request->get("album_id");
        $caption = $request->request->get("caption");

        $album = $em->getRepository("ZghFEBundle:Album")->find($album_id);

        if ($request->files->count() > 0) {
            $photo = new Photo();
            $photo->setImageFile($request->files->get("file"));
            $photo->setCaption($caption);
            $album->addPhoto($photo);
        }

        $em->persist($album);
        $em->flush();

        $url = $this->generateUrl("zgh_fe.photos_partial_albums_photos_content", [ "id" => $user->getId(), "album_id" => $album->getId() ], true);

        return new JsonResponse(array("status" => 200, "url" => $url));
    }

    public function postPhotoPhotoDeleteAction(Request $request, Photo $photo)
    {
        $user = $this->getUser();
        $album = $photo->getAlbum();
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();
        $referer = $request->headers->get("referer");
        $needles = ["zaghruta/web/app_dev.php/", "app_dev.php/"];
        $ref_route = $this->get("router")->match(str_replace($needles, "", parse_url($referer ,PHP_URL_PATH)))["_route"];
        $redirect_url = null;
        switch($ref_route){
            case "zgh_fe.photos_partial_albums_photos_content":
                $redirect_url = $this->generateUrl($ref_route, ["id" => $user->getId(), "album_id" => $album->getId()]);
                break;
            case "zgh_fe.photos.photo.content":
                $redirect_url = $this->generateUrl("zgh_fe.photos_partial_albums_photos_content", ["id" => $user->getId(), "album_id" => $album->getId()]);
                break;
            default:
                $redirect_url = $this->generateUrl($ref_route, ["id" => $user->getId()]);
        }
        return new RedirectResponse($redirect_url);
    }

    public function getPhotoCaptionIndexAction(Request $request, Photo $photo)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                "view" => $this->renderView("@ZghFE/Partial/photos/photo_caption_index.html.twig", ["photo" => $photo])
            ]);
        } else {
            return new AccessDeniedException("denied.");
        }
    }

    public function getPhotoCaptionEditAction(Request $request, Photo $photo)
    {
        if ($request->isXmlHttpRequest()) {
            if ($this->getUser()->getId() == $photo->getUser()->getId()) {
                $form = $this->createForm(new PhotoCaptionType(), $photo);
                return new JsonResponse([
                    "view" => $this->renderView("@ZghFE/Partial/photos/photo_caption_edit.html.twig", [
                        "photo" => $photo,
                        "form" => $form->createView()
                    ])
                ]);
            }
        }

        return new AccessDeniedException("denied.");
    }

    public function postPhotoCaptionEditAction(Request $request, Photo $photo)
    {
        if ($request->isXmlHttpRequest()) {
            if ($this->getUser()->getId() == $photo->getUser()->getId()) {

                $form = $this->createForm(new PhotoCaptionType(), $photo);
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $this->getDoctrine()->getManager()->persist($photo);
                    $this->getDoctrine()->getManager()->flush();
                    return new JsonResponse([
                        "view" => $this->renderView("@ZghFE/Partial/photos/photo_caption_index.html.twig", [
                            "photo" => $photo
                        ])
                    ]);
                } else {
                    return new JsonResponse([
                        "view" => $this->renderView("@ZghFE/Partial/photos/photo_caption_edit.html.twig", [
                            "photo" => $photo,
                            "form" => $form->createView()
                        ])
                    ]);
                }
            }
        }

        return new AccessDeniedException("denied.");
    }
}