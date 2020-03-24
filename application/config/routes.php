<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'Home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/*
    This route for frontend
*/


$route['login']                                     = 'Login';
$route['set-age']                                   = 'Login/setAge';
$route['signup']                                    = 'Login/signUp';
$route['do-registration']                           = 'Login/doRegistration';
$route['do-login']                                  = 'Login/doLogin';
$route['logout']                                    = 'Login/logOut';


$route['landing']                                   = 'Home/landing';
$route['profile']                                   = 'Home/profile';
$route['profile-update']                            = 'Home/profileUpdate';
$route['personal-details']                          = 'Home/personalDetails';
$route['account-settings']                          = 'Home/accountSettings';
$route['verification']                              = 'Home/verification';


$route['dashboard']                                 = 'Home/dashBoard';

$route['performer/(:any)/(:any)']                   = 'Home/viewProfile/$1/$2/';
$route['filter-performer']                          = 'Home/filterPerformer';


$route['subs-cribe']                                = 'Service/subscribe';
$route['vote']                                      = 'Service/vote';
$route['subscriptions-list']                        = 'Service/subscriptionsList';
$route['subs-suggestion']                           = 'Service/subsSuggestion';
$route['awards']                                    = 'Service/awards';

$route['my-shows']                                  = 'Service/myShows';
$route['my-subscriptions']                          = 'Service/mySubscriptions';


$route['content']                                   = 'Service/content';
$route['manage-users']                              = 'Service/manageUsers';
$route['financial']                                 = 'Service/financial';
$route['my-network']                                = 'Service/myNetwork';
$route['loyalty']                                   = 'Service/loyalty';
$route['help']                                      = 'Service/help';






$route['chat-lists']                                = 'Chat';
$route['full-chat-details']                         = 'Chat/fullChatDetails';
$route['delete-chat']                               = 'Chat/deleteChat';
$route['send-chat']                                 = 'Chat/sendChat';
$route['check-new-msg']                             = 'Chat/checkNewMsg';
$route['search-user']                               = 'Chat/searchUser';



$route['video-chat']                                = 'Videochat';
$route['start-video-chat']                          = 'Videochat/videoChatStart';
$route['check-new-video-chat-request']              = 'Videochat/checkNewVideoChatRequest';
$route['cancel-video-chat']                         = 'Videochat/cancelVideoChat';
$route['accept-video-chat']                         = 'Videochat/acceptVideoChat';
$route['check-video-chat-status']                   = 'Videochat/checkVideoChatStatus';
$route['check-video-chat-status-performer']         = 'Videochat/checkVideoChatStatusPerformer';
$route['hangup-video-chat']                         = 'Videochat/hangupVideoChat';
$route['vc-send-chat']                              = 'Videochat/vcSendChat';
$route['vc-check-new-text']                         = 'Videochat/vcCheckNewText';
$route['check-webcam-performer']                    = 'Videochat/checkWebcamPerformer';


/*
    This route for admin
*/
$route['admin']                                 = 'admin/Admin/index';
$route['admin/login']                           = 'admin/Admin/doLogin';
$route['admin/dashboard']                       = 'admin/Admin/dashboard';
$route['admin/logout']                          = 'admin/Admin/doLogout';
$route['admin/change/password']                 = 'admin/Admin/change_password';
$route['admin/profile']                         = 'admin/Admin/profile';
$route['admin/forgot/password']                 = 'admin/Admin/forget_password';
$route['admin/categories']                      = 'admin/Services/category_listing';
$route['admin/category/add_category']           = 'admin/Services/add_category';
$route['admin/credit/plans']                    = 'admin/settings/credit_plan';
$route['admin/users/list/(:any)']               = 'admin/Services/users/$1';
$route['admin/users/add-user']                  = 'admin/Services/add_user';
$route['admin/users/edit-user/(:any)']          = 'admin/Services/add_user/$1';
$route['admin/verification/performer']          = 'admin/Services/verify_performer';
$route['admin/user/details/(:any)']             = 'admin/Services/user_details/$1';
$route['admin/vote']                            = 'admin/Services/vote';
$route['admin/show-type']                       = 'admin/Services/showType';
$route['admin/add-show-type']                   = 'admin/Services/addShowType';
$route['admin/edit-show-type/(:any)']           = 'admin/Services/addShowType/$1';

$route['admin/willingness']                     = 'admin/Services/willingness';
$route['admin/add-willingness']                 = 'admin/Services/addWillingness';
$route['admin/edit-willingness/(:any)']         = 'admin/Services/addWillingness/$1';

$route['admin/appearence']                     = 'admin/Services/appearence';
$route['admin/add-appearence']                 = 'admin/Services/addAppearence';
$route['admin/edit-appearence/(:any)']         = 'admin/Services/addAppearence/$1';
