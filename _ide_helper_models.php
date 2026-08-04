<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $role
 * @property int $is_active
 * @property string|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int|null $user_id
 * @property string $action
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|activity_logs whereUserId($value)
 */
	class activity_logs extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|branches whereUpdatedAt($value)
 */
	class branches extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $visit_id
 * @property int|null $assigned_to
 * @property string $due_at
 * @property string|null $result
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereDueAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|follow_ups whereVisitId($value)
 */
	class follow_ups extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guest_categories whereUpdatedAt($value)
 */
	class guest_categories extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $guest_code
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $company_name
 * @property string|null $position
 * @property string|null $address
 * @property int|null $guest_category_id
 * @property string|null $photo_path
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereGuestCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereGuestCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|guests whereUpdatedAt($value)
 */
	class guests extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|lead_sources whereUpdatedAt($value)
 */
	class lead_sources extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $guest_id
 * @property int|null $visit_id
 * @property int|null $owner_id
 * @property string $status
 * @property numeric|null $estimated_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereEstimatedValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|leads whereVisitId($value)
 */
	class leads extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $body
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|notifications whereUserId($value)
 */
	class notifications extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $category
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|products whereUpdatedAt($value)
 */
	class products extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $role
 * @property int $is_active
 * @property string|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereUpdatedAt($value)
 */
	class users extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $visit_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_products whereVisitId($value)
 */
	class visit_products extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_purposes whereUpdatedAt($value)
 */
	class visit_purposes extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $visit_id
 * @property string|null $old_status
 * @property string $new_status
 * @property int|null $changed_by
 * @property string $changed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereChangedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereNewStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereOldStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visit_status_logs whereVisitId($value)
 */
	class visit_status_logs extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $visit_code
 * @property int $guest_id
 * @property int $branch_id
 * @property int $purpose_id
 * @property int|null $source_id
 * @property int|null $assigned_to
 * @property string|null $scheduled_at
 * @property string $check_in_at
 * @property string|null $meeting_start_at
 * @property string|null $check_out_at
 * @property string $status
 * @property int|null $queue_number
 * @property string|null $meeting_result
 * @property string|null $potential_level
 * @property string|null $next_action
 * @property string|null $follow_up_at
 * @property int $is_converted_to_lead
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereCheckInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereCheckOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereFollowUpAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereIsConvertedToLead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereMeetingResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereMeetingStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereNextAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits wherePotentialLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits wherePurposeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereQueueNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|visits whereVisitCode($value)
 */
	class visits extends \Eloquent {}
}

