<?php require_once 'includes/top.php'; ?>
 <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-aside-wrap">
                                            <div class="card-inner card-inner-lg">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="personal">
                                                        <div class="nk-block-head nk-block-head-lg">
                                                            <div class="nk-block-between">
                                                                <div class="nk-block-head-content">
                                                                    <h4 class="nk-block-title">Personal Information</h4>
                                                                    <div class="nk-block-des">
                                                                        <p>Basic info, like your name and address, that you use on Nio Platform.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                       <!-- .nk-block -->
                                                    </div><!-- .tab-pane -->
                                                    <div class="tab-pane" id="notification">
                                                        <div class="nk-block-head nk-block-head-lg">
                                                            <div class="nk-block-between">
                                                                <div class="nk-block-head-content">
                                                                    <h4 class="nk-block-title">Notification Settings</h4>
                                                                    <div class="nk-block-des">
                                                                        <p>You will get only notification what have enabled.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>Security Alerts</h6>
                                                                <p>You will get only those email notification what you want.</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <div class="g-item">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input" checked id="unusual-activity">
                                                                        <label class="custom-control-label" for="unusual-activity">Email me whenever encounter unusual activity</label>
                                                                    </div>
                                                                </div>
                                                                <div class="g-item">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input" id="new-browser">
                                                                        <label class="custom-control-label" for="new-browser">Email me if new browser is used to sign in</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>News</h6>
                                                                <p>You will get only those email notification what you want.</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <div class="g-item">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input" checked id="latest-sale">
                                                                        <label class="custom-control-label" for="latest-sale">Notify me by email about sales and latest news</label>
                                                                    </div>
                                                                </div>
                                                                <div class="g-item">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input" id="feature-update">
                                                                        <label class="custom-control-label" for="feature-update">Email me about new features and updates</label>
                                                                    </div>
                                                                </div>
                                                                <div class="g-item">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input" checked id="account-tips">
                                                                        <label class="custom-control-label" for="account-tips">Email me about tips on using account</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                    </div><!-- .tab-pane -->
                                                    <div class="tab-pane" id="settings">
                                                        <div class="nk-block-head nk-block-head-lg">
                                                            <div class="nk-block-between">
                                                                <div class="nk-block-head-content">
                                                                    <h4 class="nk-block-title">Security Settings</h4>
                                                                    <div class="nk-block-des">
                                                                        <p>These settings are helps you keep your account secure.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block">
                                                            <div class="card">
                                                                <div class="card-inner-group">
                                                                    <div class="card-inner">
                                                                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                                                                            <div class="nk-block-text">
                                                                                <h6>Save my Activity Logs</h6>
                                                                                <p>You can save your all activity logs including unusual activity detected.</p>
                                                                            </div>
                                                                            <div class="nk-block-actions">
                                                                                <ul class="align-center gx-3">
                                                                                    <li class="order-md-last">
                                                                                        <div class="custom-control custom-switch mr-n2">
                                                                                            <input type="checkbox" class="custom-control-input" checked="" id="activity-log">
                                                                                            <label class="custom-control-label" for="activity-log"></label>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="between-center flex-wrap g-3">
                                                                            <div class="nk-block-text">
                                                                                <h6>Change Password</h6>
                                                                                <p>Set a unique password to protect your account.</p>
                                                                            </div>
                                                                            <div class="nk-block-actions flex-shrink-sm-0">
                                                                                <ul class="align-center flex-wrap flex-sm-nowrap gx-3 gy-2">
                                                                                    <li class="order-md-last">
                                                                                        <a href="#" class="btn btn-primary">Change Password</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <em class="text-soft text-date fs-12px">Last changed: <span>Oct 2, 2019</span></em>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="between-center flex-wrap flex-md-nowrap g-3">
                                                                            <div class="nk-block-text">
                                                                                <h6>2 Factor Auth &nbsp; <span class="badge badge-success ml-0">Enabled</span></h6>
                                                                                <p>Secure your account with 2FA security. When it is activated you will need to enter not only your password, but also a special code using app. You can receive this code by in mobile app. </p>
                                                                            </div>
                                                                            <div class="nk-block-actions">
                                                                                <a href="#" class="btn btn-primary">Disable</a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                </div><!-- .card-inner-group -->
                                                            </div><!-- .card -->
                                                        </div><!-- .nk-block -->
                                                    </div><!-- .tab-pane -->
                                                    <div class="tab-pane" id="activity">
                                                        <div class="nk-block-head nk-block-head-lg">
                                                            <div class="nk-block-between">
                                                                <div class="nk-block-head-content">
                                                                    <h4 class="nk-block-title">Login Activity</h4>
                                                                    <div class="nk-block-des">
                                                                        <p>Here is your last 20 login activities log. <span class="text-soft"><em class="icon ni ni-info"></em></span></p>
                                                                    </div>
                                                                </div>
                                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block card">
                                                            <table class="table table-ulogs is-compact">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th class="tb-col-os"><span class="overline-title">Browser <span class="d-sm-none">/ IP</span></span></th>
                                                                        <th class="tb-col-ip"><span class="overline-title">IP</span></th>
                                                                        <th class="tb-col-time"><span class="overline-title">Time</span></th>
                                                                        <th class="tb-col-action"><span class="overline-title">&nbsp;</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">11:34 PM</span></td>
                                                                        <td class="tb-col-action"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Mozilla on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">86.188.154.225</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Nov 20, 2019 <span class="d-none d-sm-inline-block">10:34 PM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on iMac</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Nov 12, 2019 <span class="d-none d-sm-inline-block">08:56 PM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Nov 03, 2019 <span class="d-none d-sm-inline-block">04:29 PM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Mozilla on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">86.188.154.225</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Oct 29, 2019 <span class="d-none d-sm-inline-block">09:38 AM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on iMac</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Oct 23, 2019 <span class="d-none d-sm-inline-block">04:16 PM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Oct 15, 2019 <span class="d-none d-sm-inline-block">11:41 PM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Mozilla on Window</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">86.188.154.225</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Oct 13, 2019 <span class="d-none d-sm-inline-block">05:43 AM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tb-col-os">Chrome on iMac</td>
                                                                        <td class="tb-col-ip"><span class="sub-text">192.149.122.128</span></td>
                                                                        <td class="tb-col-time"><span class="sub-text">Oct 03, 2019 <span class="d-none d-sm-inline-block">04:12 AM</span></span></td>
                                                                        <td class="tb-col-action"><a href="#" class="link-cross mr-sm-n1"><em class="icon ni ni-cross"></em></a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div><!-- .nk-block-head -->
                                                    </div><!-- .tab-pane -->
                                                </div><!-- .tab-content -->
                                            </div>
                                        </div><!-- .card-aside-wrap -->
                                    </div><!-- .card -->
                                </div><!-- .nk-block -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->


<?php require_once 'includes/bottom.php'; ?>