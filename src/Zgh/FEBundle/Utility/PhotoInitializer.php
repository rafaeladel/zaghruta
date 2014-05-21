<?php
namespace Zgh\FEBundle\Utility;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Zgh\FEBundle\Entity\UserCP;
use Zgh\FEBundle\Entity\UserPP;

class PhotoInitializer
{
    protected $path;
    protected $pofile_photo;
    protected $cover_photo;

    public function __construct($path, $pp, $cp)
    {
        $this->path = $path;
        $this->pofile_photo = $pp;
        $this->cover_photo = $cp;
    }


    public function initProfilePhoto()
    {
        $pp_file = $this->locateFile($this->path, $this->pofile_photo);
        $pp = new UserPP();
        if($pp_file != null){
            $pp->setImageFile($pp_file);
        }
        return $pp;
    }

    public function initCoverPhoto()
    {
        $cp_file = $this->locateFile($this->path, $this->cover_photo);
        $cp = new UserCP();
        if($cp_file != null){
            $cp->setImageFile($cp_file);
        }
        return $cp;
    }

    protected function locateFile($path, $name)
    {
        $dest_file = null;
        $finder = new Finder();
        $finder->files()->in($path)->name($name);
        foreach ($finder as $img) {
            if(copy($img->getRealPath(), $path."/../".$img->getFilename())){
                $dest_file = new File($path."/../".$img->getFilename());
            }
        }
        return $dest_file;
    }
}