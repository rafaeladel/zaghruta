<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends  BaseUser implements ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="new_email", type="string", length=255, nullable=true, unique=true)
     * @Assert\NotBlank(groups={"change_email"}, message="New Email is required")
     * @Assert\Email(groups={"change_email", "default"})
     */
    protected $new_email;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy ="user")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy ="user")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy ="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $albums;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy ="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy ="user")
     */
    protected $likes;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="users")
     * @ORM\JoinTable(name="categories_users")
     */
    protected $interests;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $first_time;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $show_interest_notification;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy ="user", cascade={"persist","remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Wishlist", mappedBy ="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $wishlists;

    /**
     * @ORM\OneToMany(targetEntity="Experience", mappedBy ="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $experiences;

    /**
     * @ORM\OneToMany(targetEntity="Tip", mappedBy ="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $tips;

    /**
     * @ORM\OneToMany(targetEntity="Branch", mappedBy ="user", cascade={"persist","remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $branches;

    /**
     * @ORM\OneToOne(targetEntity="UserInfo", mappedBy ="user", cascade={"persist","remove"})
     */
    protected $user_info;

    /**
     * @ORM\OneToOne(targetEntity="VendorInfo", mappedBy ="user", cascade={"persist","remove"})
     */
    protected $vendor_info;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="FollowUsers", mappedBy="follower", cascade={"persist", "remove"})
     */
    protected $followers;

    /**
     * @ORM\OneToMany(targetEntity="FollowUsers", mappedBy="followee", cascade={"persist", "remove"})
     */
    protected $followees;

    /**
     * @ORM\ManyToMany(targetEntity="Zgh\FEBundle\Entity\Tag", mappedBy="users")
     *
     */
    protected $tags;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\UserPP", cascade={"persist", "remove"}, mappedBy="user")
     */
    protected $profile_photo;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\UserCP", cascade={"persist", "remove"}, mappedBy="user")
     */
    protected $cover_photo;

    /**
     * @ORM\Column(name="is_private", type="boolean")
     */
    protected $is_private;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"intro"}, message="First Name is required")
     * @Assert\Regex(
     *      groups={ "intro" },
     *      pattern= "/\pL+/u",
     *      message= "Should contain at least one alphabet character"
     * )
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"intro"}, message="Last Name is required")
     * @Assert\Regex(
     *      groups={ "intro" },
     *      pattern= "/\pL+/u",
     *      message= "Should contain at least one alphabet character"
     * )
     */
    protected $lastname;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /**
     * @ORM\OneToOne(targetEntity="UserInfo", mappedBy="relationship_user")
     */
    protected $relationship_with_table;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $emailNotification;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $new_email_token;

    /**
     * @ORM\OneToMany(targetEntity="Zgh\MsgBundle\Entity\DeletedMessage", mappedBy="user")
     */
    protected $delete_table;

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    public function __construct()
    {
        parent::__construct();
        $this->followees= new ArrayCollection();
        $this->followers= new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add posts
     *
     * @param \Zgh\FEBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\Zgh\FEBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Zgh\FEBundle\Entity\Post $posts
     */
    public function removePost(\Zgh\FEBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comments
     *
     * @param \Zgh\FEBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\Zgh\FEBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Zgh\FEBundle\Entity\Comment $comments
     */
    public function removeComment(\Zgh\FEBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add likes
     *
     * @param \Zgh\FEBundle\Entity\Like $likes
     * @return User
     */
    public function addLike(\Zgh\FEBundle\Entity\Like $likes)
    {
        $this->likes[] = $likes;

        return $this;
    }

    /**
     * Remove likes
     *
     * @param \Zgh\FEBundle\Entity\Like $likes
     */
    public function removeLike(\Zgh\FEBundle\Entity\Like $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add products
     *
     * @param \Zgh\FEBundle\Entity\Product $products
     * @return User
     */
    public function addProduct(\Zgh\FEBundle\Entity\Product $products)
    {
        $this->products[] = $products;
        $products->setUser($this);
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Zgh\FEBundle\Entity\Product $products
     */
    public function removeProduct(\Zgh\FEBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add wishlists
     *
     * @param \Zgh\FEBundle\Entity\Wishlist $wishlists
     * @return User
     */
    public function addWishlist(\Zgh\FEBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists[] = $wishlists;
        $wishlists->setUser($this);
        return $this;
    }

    /**
     * Remove wishlists
     *
     * @param \Zgh\FEBundle\Entity\Wishlist $wishlists
     */
    public function removeWishlist(\Zgh\FEBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists->removeElement($wishlists);
    }

    /**
     * Get wishlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWishlists()
    {
        return $this->wishlists;
    }

    /**
     * Add experiences
     *
     * @param \Zgh\FEBundle\Entity\Experience $experiences
     * @return User
     */
    public function addExperience(\Zgh\FEBundle\Entity\Experience $experiences)
    {
        $this->experiences[] = $experiences;
        $experiences->setUser($this);
        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \Zgh\FEBundle\Entity\Experience $experiences
     */
    public function removeExperience(\Zgh\FEBundle\Entity\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Add branches
     *
     * @param \Zgh\FEBundle\Entity\Branch $branches
     * @return User
     */
    public function addBranch(\Zgh\FEBundle\Entity\Branch $branches)
    {
        $this->branches[] = $branches;
        $branches->setUser($this);
        return $this;
    }

    /**
     * Remove branches
     *
     * @param \Zgh\FEBundle\Entity\Branch $branches
     */
    public function removeBranch(\Zgh\FEBundle\Entity\Branch $branches)
    {
        $this->branches->removeElement($branches);
    }

    /**
     * Get branches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Add interests
     *
     * @param \Zgh\FEBundle\Entity\Category $interests
     * @return User
     */
    public function addInterest(\Zgh\FEBundle\Entity\Category $interests)
    {
        $this->interests[] = $interests;

        return $this;
    }

    /**
     * Remove interests
     *
     * @param \Zgh\FEBundle\Entity\Category $interests
     */
    public function removeInterest(\Zgh\FEBundle\Entity\Category $interests)
    {
        $this->interests->removeElement($interests);
    }

    /**
     * Get interests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInterests()
    {
        return $this->interests;
    }   

    /**
     * Set user_info
     *
     * @param \Zgh\FEBundle\Entity\UserInfo $userInfo
     * @return User
     */
    public function setUserInfo(\Zgh\FEBundle\Entity\UserInfo $userInfo = null)
    {
        $this->user_info = $userInfo;
        $userInfo->setUser($this);
        return $this;
    }

    /**
     * Get user_info
     *
     * @return \Zgh\FEBundle\Entity\UserInfo 
     */
    public function getUserInfo()
    {
        return $this->user_info;
    }

    /**
     * Set vendor_info
     *
     * @param \Zgh\FEBundle\Entity\VendorInfo $vendorInfo
     * @return User
     */
    public function setVendorInfo(\Zgh\FEBundle\Entity\VendorInfo $vendorInfo = null)
    {
        $this->vendor_info = $vendorInfo;
        $vendorInfo->setUser($this);
        return $this;
    }

    /**
     * Get vendor_info
     *
     * @return \Zgh\FEBundle\Entity\VendorInfo 
     */
    public function getVendorInfo()
    {
        return $this->vendor_info;
    }

    /**
     * Set first_time
     *
     * @param boolean $firstTime
     * @return User
     */
    public function setFirstTime($firstTime)
    {
        $this->first_time = $firstTime;

        return $this;
    }

    /**
     * Get first_time
     *
     * @return boolean 
     */
    public function getFirstTime()
    {
        return $this->first_time;
    }

    /**
     * @ORM\PrePersist
     */
    public function setFirstTimeValue()
    {
        if($this->first_time == null) {
            $this->setFirstTime(true);
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function setIsPrivateValue()
    {
        $this->setIsPrivate(false);
    }

    /**
     * Add albums
     *
     * @param \Zgh\FEBundle\Entity\Album $albums
     * @return User
     */
    public function addAlbum(\Zgh\FEBundle\Entity\Album $albums)
    {
        $albums->setUser($this);
        $this->albums[] = $albums;

        return $this;
    }

    /**
     * Remove albums
     *
     * @param \Zgh\FEBundle\Entity\Album $albums
     */
    public function removeAlbum(\Zgh\FEBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Add photos
     *
     * @param \Zgh\FEBundle\Entity\Photo $photos
     * @return User
     */
    public function addPhoto(\Zgh\FEBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Zgh\FEBundle\Entity\Photo $photos
     */
    public function removePhoto(\Zgh\FEBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set profile_photo
     *
     * @param \Zgh\FEBundle\Entity\UserPP $profilePhoto
     * @return User
     */
    public function setProfilePhoto(\Zgh\FEBundle\Entity\UserPP $profilePhoto = null)
    {
        $this->profile_photo = $profilePhoto;
        $profilePhoto->setUser($this);
        return $this;
    }

    /**
     * Get profile_photo
     *
     * @return \Zgh\FEBundle\Entity\UserPP 
     */
    public function getProfilePhoto()
    {
        return $this->profile_photo;
    }

    /**
     * Set cover_photo
     *
     * @param \Zgh\FEBundle\Entity\UserCP $coverPhoto
     * @return User
     */
    public function setCoverPhoto(\Zgh\FEBundle\Entity\UserCP $coverPhoto = null)
    {
        $this->cover_photo = $coverPhoto;
        $coverPhoto->setUser($this);
        return $this;
    }

    /**
     * Get cover_photo
     *
     * @return \Zgh\FEBundle\Entity\UserCP 
     */
    public function getCoverPhoto()
    {
        return $this->cover_photo;
    }




    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }


    /**
     * Set followers
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followers
     * @return User
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;

        return $this;
    }

    /**
     * Get followers
     *
     * @return \Zgh\FEBundle\Entity\FollowUsers 
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set followees
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followees
     * @return User
     */
    public function setFollowees(\Zgh\FEBundle\Entity\FollowUsers $followees = null)
    {
        $this->followees = $followees;

        return $this;
    }

    /**
     * Get followees
     *
     * @return \Zgh\FEBundle\Entity\FollowUsers 
     */
    public function getFollowees()
    {
        return $this->followees;
    }

    /**
     * Set is_private
     *
     * @param boolean $isPrivate
     * @return User
     */
    public function setIsPrivate($isPrivate)
    {
        $this->is_private = $isPrivate;

        return $this;
    }

    /**
     * Get is_private
     *
     * @return boolean 
     */
    public function getIsPrivate()
    {
        return $this->is_private;
    }

    /**
     * Add followers
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followers
     * @return User
     */
    public function addFollower(\Zgh\FEBundle\Entity\FollowUsers $followers)
    {
        $this->followers[] = $followers;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followers
     */
    public function removeFollower(\Zgh\FEBundle\Entity\FollowUsers $followers)
    {
        $this->followers->removeElement($followers);
    }

    /**
     * Add followees
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followees
     * @return User
     */
    public function addFollowee(\Zgh\FEBundle\Entity\FollowUsers $followees)
    {
        $this->followees[] = $followees;

        return $this;
    }

    /**
     * Remove followees
     *
     * @param \Zgh\FEBundle\Entity\FollowUsers $followees
     */
    public function removeFollowee(\Zgh\FEBundle\Entity\FollowUsers $followees)
    {
        $this->followees->removeElement($followees);
    }

    /**
     * Set show_interest_notification
     *
     * @param boolean $showInterestNotification
     * @return User
     */
    public function setShowInterestNotification($showInterestNotification)
    {
        $this->show_interest_notification = $showInterestNotification;

        return $this;
    }

    /**
     * Get show_interest_notification
     *
     * @return boolean 
     */
    public function getShowInterestNotification()
    {
        return $this->show_interest_notification;
    }

    /**
     * @ORM\PrePersist
     */
    public function setShowInterestNotificationValue()
    {
        if(in_array("ROLE_CUSTOMER", $this->getRoles()))
        {
            $this->setShowInterestNotification(true);
        }
        elseif(in_array("ROLE_VENDOR", $this->getRoles()))
        {
            $this->setShowInterestNotification(false);

        }
    }

    /**
     * Set relationship_with_table
     *
     * @param \Zgh\FEBundle\Entity\UserInfo $relationshipWithTable
     * @return User
     */
    public function setRelationshipWithTable(\Zgh\FEBundle\Entity\UserInfo $relationshipWithTable = null)
    {
        $this->relationship_with_table = $relationshipWithTable;

        return $this;
    }

    /**
     * Get relationship_with_table
     *
     * @return \Zgh\FEBundle\Entity\UserInfo 
     */
    public function getRelationshipWithTable()
    {
        return $this->relationship_with_table;
    }

    public function  __toString(){
        return $this->getFullName();
    }

    /**
     * Add tips
     *
     * @param \Zgh\FEBundle\Entity\Tip $tips
     * @return User
     */
    public function addTip(\Zgh\FEBundle\Entity\Tip $tips)
    {
        $this->tips[] = $tips;
        $tips->setUser($this);
        return $this;
    }

    /**
     * Remove tips
     *
     * @param \Zgh\FEBundle\Entity\Tip $tips
     */
    public function removeTip(\Zgh\FEBundle\Entity\Tip $tips)
    {
        $this->tips->removeElement($tips);
    }

    /**
     * Get tips
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTips()
    {
        return $this->tips;
    }

    /**
     * Add notifications
     *
     * @param \Zgh\FEBundle\Entity\Notification $notifications
     * @return User
     */
    public function addNotification(\Zgh\FEBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;
        $notifications->setUser($this);
        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Zgh\FEBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Zgh\FEBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);
    }



    /**
     * Set emailNotification
     *
     * @param boolean $emailNotification
     * @return User
     */
    public function setEmailNotification($emailNotification)
    {
        $this->emailNotification = $emailNotification;

        return $this;
    }

    /**
     * Get emailNotification
     *
     * @return boolean 
     */
    public function getEmailNotification()
    {
        return $this->emailNotification;
    }

    /**
     * @ORM\PrePersist
     */
    public function setEmailNotificationValue()
    {
        $this->setEmailNotification(true);
    }

    /**
     * Set new_email
     *
     * @param string $newEmail
     * @return User
     */
    public function setNewEmail($newEmail)
    {
        $this->new_email = $newEmail;

        return $this;
    }

    /**
     * Get new_email
     *
     * @return string 
     */
    public function getNewEmail()
    {
        return $this->new_email;
    }

    /**
     * Set new_email_token
     *
     * @param string $newEmailToken
     * @return User
     */
    public function setNewEmailToken($newEmailToken)
    {
        $this->new_email_token = $newEmailToken;

        return $this;
    }

    /**
     * Get new_email_token
     *
     * @return string 
     */
    public function getNewEmailToken()
    {
        return $this->new_email_token;
    }

    /**
     * Add delete_table
     *
     * @param \Zgh\MsgBundle\Entity\DeletedMessage $deleteTable
     * @return User
     */
    public function addDeleteTable(\Zgh\MsgBundle\Entity\DeletedMessage $deleteTable)
    {
        $this->delete_table[] = $deleteTable;

        return $this;
    }

    /**
     * Remove delete_table
     *
     * @param \Zgh\MsgBundle\Entity\DeletedMessage $deleteTable
     */
    public function removeDeleteTable(\Zgh\MsgBundle\Entity\DeletedMessage $deleteTable)
    {
        $this->delete_table->removeElement($deleteTable);
    }

    /**
     * Get delete_table
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeleteTable()
    {
        return $this->delete_table;
    }

    /**
     * Add tags
     *
     * @param \Zgh\FEBundle\Entity\Tag $tags
     * @return User
     */
    public function addTag(\Zgh\FEBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Zgh\FEBundle\Entity\Tag $tags
     */
    public function removeTag(\Zgh\FEBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}
