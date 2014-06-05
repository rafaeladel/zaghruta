<?php
namespace Zgh\FEBundle\Model\Event;

class NotifyEvents
{
    const NOTIFY_LIKE = "notify.like";
    const NOTIFY_COMMENT = "notify.comment";
    const NOTIFY_FOLLOW = "notify.follow";
    const NOTIFY_FOLLOW_REQUEST = "notify.follow_request";
    const NOTIFY_DELETE = "notify.delete";
}