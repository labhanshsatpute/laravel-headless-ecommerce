<?php 

namespace App\Enums;

enum Permission: string
{
    case VIEW_PRODUCT = 'VIEW_PRODUCT';
    case CREATE_PRODUCT = 'CREATE_PRODUCT';
    case UPDATE_PRODUCT = 'UPDATE_PRODUCT';
    case DELETE_PRODUCT = 'DELETE_PRODUCT';

    case VIEW_COUPON = 'VIEW_COUPON';
    case CREATE_COUPON = 'CREATE_COUPON';
    case UPDATE_COUPON = 'UPDATE_COUPON';
    case DELETE_COUPON = 'DELETE_COUPON';

    case VIEW_ACCESS = 'VIEW_ACCESS';
    case CREATE_ACCESS = 'CREATE_ACCESS';
    case UPDATE_ACCESS = 'UPDATE_ACCESS';
    case DELETE_ACCESS = 'DELETE_ACCESS';

    case VIEW_NEWSLETTER_EMAIL = 'VIEW_NEWSLETTER_EMAIL';
    case PUBLISH_NEWSLETTER = 'PUBLISH_NEWSLETTER';

    case VIEW_REVIEW = 'VIEW_REVIEW';
    case UPDATE_REVIEW = 'UPDATE_REVIEW';
    case DELETE_REVIEW = 'DELETE_REVIEW';

    case VIEW_USER = 'VIEW_USER';
    case VIEW_UPDATE = 'VIEW_UPDATE';

    case VIEW_CATEGORY = 'VIEW_CATEGORY';
    case CREATE_CATEGORY = 'CREATE_CATEGORY';
    case UPDATE_CATEGORY = 'UPDATE_CATEGORY';
    case DELETE_CATEGORY = 'DELETE_CATEGORY';

    case VIEW_ORDER = 'VIEW_ORDER';
    case UPDATE_ORDER = 'UPDATE_ORDER';
    case DELETE_ORDER = 'DELETE_ORDER';

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}