<?php

namespace app\constants;

/**
 * Class Messages
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\constants
 */
class Messages
{
    const INVITE_ALREADY_CANCELLED = 'This invitation has expired, please contact System Administrator';
    const INVITE_NOT_FOUND = 'Invite not found';
    const INVITE_ALREADY_ACCEPTED = 'You have already accepted this invite';
    const INVITE_ALREADY_SENT = 'You have already sent an invite to one or more email provided';
    const INVITE_EXPIRED = 'Sorry, this invite has expired';
    const DUPLICATE_EMAILS_IN_INVITES = 'Please remove duplicated email addresses in the invite list';
    const INVALID_EMAIL = 'One or more invalid emails supplied';
    const INVALID_EMAIL_ADDRESS = 'Email address is invalid';
    const ENTITY_INVITE = 'Invite';

    const INVALID_PASSWORD_RESET_TOKEN = 'Token not found';
    const PASSWORD_RESET_EXPIRED = 'This token has expired, please contact System Administrator';

    const UPDATE_VIOLATION = 'Violation';
    const EMPTY_VIOLATION = 'Types of Violation cannot be empty';
    const INVALID_ROLE_CREATED = 'This role already exists';
    const UPDATE_REPORT = 'Report';

    const TASK_CANCEL = 'cancelled';
    const TASK_SEND = 'sent';
    const TASK_SAVE = 'saved';
    const TASK_CREATE = 'created';
    const TASK_UPDATE = 'updated';
    const NEW_ROLE = 'Role and permissions';

    const ACTION_CANCEL = 'CANCEL';
    const ACTION_UPDATE = 'UPDATE';

    const NO = 'NO';
    const YES = 'YES';

    const PERMISSION_NOT_GRANTED = 'You do not have the permission to access the desired page';
    const VIOLATION_ADDED = 'Violation Type Successfully Added';
    const COULD_NOT_SEND_PASSWORD_RESET_EMAIL = 'Could not send password reset email';
    const VIOLATION_EXIST = 'Violation type already exists';
    const NEW_CASE = 'New Case is Successfully Added';
    const CASE_EXIST = 'Case already exist';
    const ACTOR_EXIST = 'This actor already exists';
    const CASE_RESOLVED = 'Case';
    const EMPTY_ACTION = 'No action Selected';
    const CASE_REASSIGNED = 'Reassigned';
    const CASE_ASSIGNED = 'Case assigned, You can still assign to another actor';

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @param $action
     * @return string
     */
    public static function getWarningMessage($entity, $action)
    {
        return sprintf('Are you sure you want to %s this %s?', $action, $entity);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @param $task
     * @return string
     */
    public static function getSuccessMessage($entity, $task)
    {
        return sprintf('%s %s successfully', $entity, $task);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @return string
     */
    public static function getNotFoundMessage($entity = 'Record')
    {
        return sprintf('%s not found', $entity);
    }

    public static function getFailureMessage($entity, $task)
    {
        return sprintf('%s not %s successfully', $entity, $task);
    }

    public static function getNotExistWarning($entity)
    {
        return sprintf('%s does not exist', $entity);
    }
}
