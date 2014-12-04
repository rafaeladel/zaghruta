<?php
namespace Zgh\FEBundle\Model\Event;

class NotifyEvents
{
    const NOTIFY_LIKE = "notify.like";
    const NOTIFY_COMMENT = "notify.comment";
    const NOTIFY_COMMENT_OTHER = "notify.comment_other";
    const NOTIFY_FOLLOW = "notify.follow";
    const NOTIFY_FOLLOW_REQUEST = "notify.follow_request";
    const NOTIFY_FOLLOW_REQUEST_ACCEPTED = "notify.follow_request_accepted";
    const NOTIFY_DELETE = "notify.delete";
    const NOTIFY_RELATIONSHIP_REQUEST = "notify.relationship.request";
    const NOTIFY_MESSAGE = "notify.message";
}