<aside class="main-sidebar" id="alert2">
    <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>
        <form class="navbar-form navbar-left search-form2" role="search"  action="<?php echo site_url('admin/admin/search'); ?>" method="POST">
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="input-group ">

                <input type="text"  name="search_text" class="form-control search-form" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" style="padding: 3px 12px !important;border-radius: 0px 30px 30px 0px; background: #fff;" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
    <?php }?>
    <section class="sidebar" id="sibe-box">
        <?php $this->load->view('layout/top_sidemenu');?>
        <ul class="sidebar-menu verttop">
            <?php
if ($this->module_lib->hasActive('receptioniste')) {
    if ((

        $this->rbac->hasPrivilege('visiteurs', 'can_view') ||
        $this->rbac->hasPrivilege('journal_appels', 'can_view') ||
        $this->rbac->hasPrivilege('courier_envoyer', 'can_view') ||
        $this->rbac->hasPrivilege('courier_reçu', 'can_view') ||
        $this->rbac->hasPrivilege('reclamation', 'can_view') ||
        $this->rbac->hasPrivilege('parametre', 'can_view'))) {
        ?>

                    <li class="treeview <?php echo set_Topmenu('receptioniste'); ?>">
                        <a href="#">
                            <i class="fa fa-ioxhost ftlayer"></i> <span><?php echo $this->lang->line('front_office'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('admission_enquiry', 'can_view')) {?>

                               <!-- <li class="<?php echo set_Submenu('admin/enquiry'); ?>"><a href="<?php echo base_url(); ?>admin/enquiry"><i class="fa fa-angle-double-right"></i> Demande de requêtes </a></li>-->

                                <?php
}
        if ($this->rbac->hasPrivilege('visiteurs', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/visitors'); ?>"><a href="<?php echo base_url(); ?>admin/visitors"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('visitor_book'); ?></a></li>

                                <?php
}
                        /*    if ($this->rbac->hasPrivilege('visitor_bok', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/files'); ?>"><a href="<?php echo base_url(); ?>admin/files"><i class="fa fa-angle-double-right"></i> Files</a></li>

                                <?php
                            }*/
        if ($this->rbac->hasPrivilege('journal_appels', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('admin/generalcall'); ?>"><a href="<?php echo base_url(); ?>admin/generalcall"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('phone_call_log'); ?></a></li>

                                <?php
}
        if ($this->rbac->hasPrivilege('courier_envoyer', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('admin/dispatch'); ?>"><a href="<?php echo base_url(); ?>admin/dispatch"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('postal_dispatch'); ?></a></li>

                                <?php
}
        if ($this->rbac->hasPrivilege('courier_reçu', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('admin/receive'); ?>"><a href="<?php echo base_url(); ?>admin/receive"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('postal_receive'); ?></a></li>

                                <?php
}
        if ($this->rbac->hasPrivilege('reclamation', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('admin/complaint'); ?>"><a href="<?php echo base_url(); ?>admin/complaint"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('complain'); ?></a></li>

                                <?php
}
        if ($this->rbac->hasPrivilege('parametre', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('admin/visitorspurpose'); ?>"><a href="<?php echo base_url(); ?>admin/visitorspurpose"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('setup_front_office'); ?></a></li>

                            <?php }?>
                        </ul>
                    </li>
                    <?php
}
}

/*if ($this->module_lib->hasActive('student_information')) {
    if (($this->rbac->hasPrivilege('student', 'can_view') ||
        $this->rbac->hasPrivilege('student', 'can_add') ||
        $this->rbac->hasPrivilege('student_history', 'can_view') ||
        $this->rbac->hasPrivilege('student_categories', 'can_view') ||
        $this->rbac->hasPrivilege('student_houses', 'can_view') ||
        $this->rbac->hasPrivilege('disable_student', 'can_view') || $this->rbac->hasPrivilege('disable_reason', 'can_view') || $this->rbac->hasPrivilege('online_admission', 'can_view') || $this->rbac->hasPrivilege('multiclass_student', 'can_view') || $this->rbac->hasPrivilege('disable_reason', 'can_view'))) {
        ?>


                    <li class="treeview <?php echo set_Topmenu('Student Information'); ?>">
                        <a href="#">
                            <i class="fa fa-user-plus ftlayer"></i> <span><?php echo $this->lang->line('student_information'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('student', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('student/search'); ?>"><a href="<?php echo base_url(); ?>student/search"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student_details'); ?></a></li>

                                <?php
}

        if ($this->rbac->hasPrivilege('student', 'can_add')) {
            ?>

                                <li class="<?php echo set_Submenu('student/create'); ?>"><a href="<?php echo base_url(); ?>student/create"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student_admission'); ?></a></li>
                            <?php }?><?php
if ($this->module_lib->hasActive('online_admission')) {
            if ($this->rbac->hasPrivilege('online_admission', 'can_view')) {
                ?>

                                    <li class="<?php echo set_Submenu('onlinestudent'); ?>"><a href="<?php echo site_url('admin/onlinestudent'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('online') . " " . $this->lang->line('admission'); ?></a></li>

                                    <?php
}
        }

        if ($this->rbac->hasPrivilege('disable_student', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('student/disablestudentslist'); ?>"><a href="<?php echo base_url(); ?>student/disablestudentslist"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('disabled_students'); ?></a></li>
                                <?php
}
        if ($this->module_lib->hasActive('multi_class')) {
            if ($this->rbac->hasPrivilege('multi_class_student', 'can_view')) {
                ?>
                                    <li class="<?php echo set_Submenu('student/multiclass'); ?>"><a href="<?php echo base_url(); ?>student/multiclass"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('multiclass') . " " . $this->lang->line('student'); ?></a></li>
                                    <?php
}
        }
        if ($this->rbac->hasPrivilege('student', 'can_delete')) {
            ?>
                                <li class="<?php echo set_Submenu('bulkdelete'); ?>"><a href="<?php echo site_url('student/bulkdelete'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('bulk') . " " . $this->lang->line('delete'); ?></a>
                                </li>
                                <?php
}

        if ($this->rbac->hasPrivilege('student_categories', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('category/index'); ?>"><a href="<?php echo base_url(); ?>category"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student_categories'); ?></a></li>

                            <?php }
        ?>
                            <?php
if ($this->rbac->hasPrivilege('student_houses', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/schoolhouse'); ?>"><a href="<?php echo base_url(); ?>admin/schoolhouse"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('house'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('disable_reason', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('student/disable_reason'); ?>"><a href="<?php echo base_url(); ?>admin/disable_reason"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('disable') . " " . $this->lang->line('reason'); ?></a></li>
                                <?php
}
        ?>


                        </ul>
                    </li>
                    <?php
}
}*/

if ($this->module_lib->hasActive('fees_collection')) {
    if (($this->rbac->hasPrivilege('collect_fees', 'can_view') ||
        $this->rbac->hasPrivilege('search_fees_payment', 'can_view') ||
        $this->rbac->hasPrivilege('search_due_fees', 'can_view') ||
        $this->rbac->hasPrivilege('fees_statement', 'can_view') ||
        $this->rbac->hasPrivilege('fees_carry_forward', 'can_view') ||
        $this->rbac->hasPrivilege('fees_master', 'can_view') ||
        $this->rbac->hasPrivilege('fees_group', 'can_view') ||
        $this->rbac->hasPrivilege('fees_type', 'can_view') ||
        $this->rbac->hasPrivilege('fees_discount', 'can_view') ||
        $this->rbac->hasPrivilege('accountants', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Fees Collection'); ?>">
                        <a href="#">
                            <i class="fa fa-money ftlayer"></i> <span> <?php echo $this->lang->line('fees_collection'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('studentfee/index'); ?>"><a href="<?php echo base_url(); ?>studentfee"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('collect_fees'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('studentfee/searchpayment'); ?>"><a href="<?php echo base_url(); ?>studentfee/searchpayment"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('search_fees_payment'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('studentfee/feesearch'); ?>"><a href="<?php echo base_url(); ?>studentfee/feesearch"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('search_due_fees'); ?> </a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('fees_master', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/feemaster'); ?>"><a href="<?php echo base_url(); ?>admin/feemaster"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees_master'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('fees_group', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/feegroup'); ?>"><a href="<?php echo base_url(); ?>admin/feegroup"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees_group'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('fees_type', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('feetype/index'); ?>"><a href="<?php echo base_url(); ?>admin/feetype"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees_type'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('fees_discount', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/feediscount'); ?>"><a href="<?php echo base_url(); ?>admin/feediscount"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees_discount'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('fees_carry_forward', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('feesforward/index'); ?>"><a href="<?php echo base_url('admin/feesforward'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees_carry_forward'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('fees_reminder', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('feereminder/setting'); ?>"><a href="<?php echo site_url('admin/feereminder/setting'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('fees') . " " . $this->lang->line('reminder'); ?></a></li>
                                <?php
}
        ?>

                        </ul>
                    </li>
                    <?php
}
}
if ($this->module_lib->hasActive('caisse')) {
    if (($this->rbac->hasPrivilege('caisse', 'can_view') ||
        $this->rbac->hasPrivilege('recherche_caisse', 'can_view') ||
        $this->rbac->hasPrivilege('categorie_caisse', 'can_view'))) {
        ?>

                    <li class="treeview <?php echo set_Topmenu('Caisse'); ?>">
                        <a href="#">
                            <i class="fa fa-usd ftlayer"></i> <span><?php echo $this->lang->line('income'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('caisse', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('income/index'); ?>"><a href="<?php echo base_url(); ?>admin/income"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('add_income'); ?></a></li>
                                <?php
}
       /* if ($this->rbac->hasPrivilege('search_income', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('income/incomesearch'); ?>"><a href="<?php echo base_url(); ?>admin/income/incomesearch"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('search_income'); ?></a></li>
                                <?php
}*/
        if ($this->rbac->hasPrivilege('categorie_caisse', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('incomeshead/index'); ?>"><a href="<?php echo base_url(); ?>admin/incomehead"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('income_head'); ?></a></li>
                            <?php }?>
                            <a href="#">
                                <i style="background-color: white;margin-left: 10px" class="fa fa-credit-card ftlayer"></i> <span style="color: white;margin: 13px"><?php echo $this->lang->line('expenses'); ?></span> <i class="fa fa-angle-right pull-right"></i>
                            </a>

                            <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('expense/index'); ?>"><a href="<?php echo base_url(); ?>admin/expense"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('add_expense'); ?></a></li>

                                   <!-- <li class="<?php echo set_Submenu('expense/expensesearch'); ?>"><a href="<?php echo base_url(); ?>admin/expense/expensesearch"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('search_expense'); ?></a></li>-->

                                    <li class="<?php echo set_Submenu('expenseshead/index'); ?>"><a href="<?php echo base_url(); ?>admin/expensehead"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('expense_head'); ?></a></li>

                            </ul>
                        </ul>

                    </li>
                    <?php
}
}
/*if ($this->module_lib->hasActive('depense')) {
    if (($this->rbac->hasPrivilege('depense', 'can_view') ||
        $this->rbac->hasPrivilege('recherche_depense', 'can_view') ||
        $this->rbac->hasPrivilege('categorie_depense', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Depense'); ?>">
                        <a href="#">
                            <i class="fa fa-credit-card ftlayer"></i> <span><?php echo $this->lang->line('expenses'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('depense', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('expense/index'); ?>"><a href="<?php echo base_url(); ?>admin/expense"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('add_expense'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('search_expense', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('expense/expensesearch'); ?>"><a href="<?php echo base_url(); ?>admin/expense/expensesearch"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('search_expense'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('categorie_depense', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('expenseshead/index'); ?>"><a href="<?php echo base_url(); ?>admin/expensehead"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('expense_head'); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <?php
}
}*/

if ($this->module_lib->hasActive('student_attendance')) {
    if (($this->rbac->hasPrivilege('student_attendance', 'can_view') ||
        $this->rbac->hasPrivilege('student_attendance_report', 'can_view') ||
        $this->rbac->hasPrivilege('attendance_report', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Attendance'); ?>">
                        <a href="#">
                            <i class="fa fa-calendar-check-o ftlayer"></i> <span><?php echo $this->lang->line('attendance'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if (!is_subAttendence()) {
            if ($this->rbac->hasPrivilege('student_attendance', 'can_view')) {
                ?>
                                    <li class="<?php echo set_Submenu('stuattendence/index'); ?>"><a href="<?php echo base_url(); ?>admin/stuattendence"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student_attendance'); ?></a></li>
                                    <?php
}
            if ($this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
                ?>
                                    <li class="<?php echo set_Submenu('stuattendence/attendenceReport'); ?>"><a href="<?php echo base_url(); ?>admin/stuattendence/attendencereport"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('attendance_by_date'); ?></a></li>
                                    <?php
}
        } else {
            if ($this->rbac->hasPrivilege('student_attendance', 'can_view')) {
                ?>
                                    <li class="<?php echo set_Submenu('subjectattendence/index'); ?>"><a href="<?php echo base_url(); ?>admin/subjectattendence"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('period') . " " . $this->lang->line('attendance'); ?></a></li>
                                    <?php
}
            if ($this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
                ?>


                                    <li class="<?php echo set_Submenu('subjectattendence/reportbydate'); ?>"><a href="<?php echo site_url('admin/subjectattendence/reportbydate'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('period') . " " . $this->lang->line('attendance') . " " . $this->lang->line('by') . " " . $this->lang->line('date'); ?></a></li>

                                    <?php
}
        }
        if ($this->rbac->hasPrivilege('approve_leave', 'can_view')) {
            ?>


                                <li class="<?php echo set_Submenu('Attendance/approve_leave'); ?>"><a href="<?php echo base_url(); ?>admin/approve_leave"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('approve') . " " . $this->lang->line('leave'); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <?php
}
    ?>

                <?php
}
if ($this->module_lib->hasActive('examination')) {
    if (($this->rbac->hasPrivilege('exam_group', 'can_view') ||
        $this->rbac->hasPrivilege('exam_result', 'can_view') ||
        $this->rbac->hasPrivilege('design_admit_card', 'can_view') ||
        $this->rbac->hasPrivilege('print_admit_card', 'can_view') ||
        $this->rbac->hasPrivilege('design_marksheet', 'can_view') ||
        $this->rbac->hasPrivilege('print_marksheet', 'can_view') ||
        $this->rbac->hasPrivilege('marks_grade', 'can_view')
    )) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Examinations'); ?>">
                        <a href="#">
                            <i class="fa fa-map-o ftlayer"></i> <span><?php echo $this->lang->line('examinations'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <?php if ($this->rbac->hasPrivilege('exam_group', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('Examinations/examgroup'); ?>"><a href="<?php echo site_url('admin/examgroup'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam') . " " . $this->lang->line('group') ?></a></li>
                            <?php }
        ?>
                            <li class="<?php echo set_Submenu('Examinations/Examschedule'); ?>"><a href="<?php echo site_url('admin/exam_schedule'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam_schedule'); ?></a></li>
                            <?php if ($this->rbac->hasPrivilege('exam_result', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/Examresult'); ?>"><a href="<?php echo site_url('admin/examresult'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam') . " " . $this->lang->line('result'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('design_admit_card', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/admitcard'); ?>"><a href="<?php echo base_url(); ?>admin/admitcard"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('design') . " " . $this->lang->line('admit') . " " . $this->lang->line('card'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('print_admit_card', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/examresult/admitcard'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/admitcard"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('print') . " " . $this->lang->line('admit') . " " . $this->lang->line('card'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('design_marksheet', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/marksheet'); ?>"><a href="<?php echo site_url('admin/marksheet'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('design') . " " . $this->lang->line('marksheet') ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('print_marksheet', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/examresult/marksheet'); ?>"><a href="<?php echo base_url(); ?>admin/examresult/marksheet"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('print') . " " . $this->lang->line('marksheet'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('marks_grade', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Examinations/grade'); ?>"><a href="<?php echo base_url(); ?>admin/grade"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('marks_grade'); ?></a></li> <?php }?>



                        </ul>
                    </li>
                <?php
}
}
?>

            <?php
if ($this->module_lib->hasActive('online_examination')) {
    if (($this->rbac->hasPrivilege('online_examination', 'can_view') || $this->rbac->hasPrivilege('question_bank', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Online_Examinations'); ?>">
                        <a href="#">
                            <i class="fa fa-rss ftlayer"></i> <span><?php echo $this->lang->line('online') . " " . $this->lang->line('examinations'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('online_examination', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Online_Examinations/Onlineexam'); ?>"><a href="<?php echo base_url(); ?>admin/onlineexam"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('online') . " " . $this->lang->line('exam'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('question_bank', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Online_Examinations/question'); ?>"><a href="<?php echo base_url(); ?>admin/question"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('question') . " " . $this->lang->line('bank'); ?></a></li>
                                <?php
}
        ?>



                        </ul>
                    </li>
                    <?php
}
}
?> <?php
if ($this->module_lib->hasActive('lesson_plan')) {
    if (($this->rbac->hasPrivilege('manage_lesson_plan', 'can_view')) || ($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view')) || ($this->rbac->hasPrivilege('lesson', 'can_view')) || ($this->rbac->hasPrivilege('topic', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('lessonplan'); ?>">
                        <a href="#">
                            <i class="fa fa-list-alt ftlayer"></i> <span><?php echo $this->lang->line('lesson_plan'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/syllabus'); ?>"><a href="<?php echo base_url(); ?>admin/syllabus"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('manage_lesson_plan'); ?></a></li>
                            <?php }?>
                            <?php if ($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/lessonplan'); ?>"><a href="<?php echo base_url(); ?>admin/syllabus/status"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('manage_syllabus_status'); ?></a></li>
                            <?php }if ($this->rbac->hasPrivilege('lesson', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/lessonplan/lesson'); ?>"><a href="<?php echo base_url(); ?>admin/lessonplan/lesson"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('lesson'); ?></a></li>
                            <?php }if ($this->rbac->hasPrivilege('topic', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/lessonplan/topic'); ?>"><a href="<?php echo base_url(); ?>admin/lessonplan/topic"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('topic'); ?></a></li>
        <?php }?>

                        </ul>
                    </li>
                <?php }
}
?>
            <?php
            if ($this->module_lib->hasActive('service')) {
    if (($this->rbac->hasPrivilege('class_timetable', 'can_view') ||
        $this->rbac->hasPrivilege('teachers_timetable', 'can_view') ||
        $this->rbac->hasPrivilege('assign_class_teacher', 'can_view') ||
        $this->rbac->hasPrivilege('promote_student', 'can_view') ||
        $this->rbac->hasPrivilege('subject_group', 'can_view') ||
        $this->rbac->hasPrivilege('file', 'can_view') ||
          $this->rbac->hasPrivilege('projet', 'can_view') ||
        $this->rbac->hasPrivilege('class', 'can_view') ||
        $this->rbac->hasPrivilege('section', 'can_view')
    )) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Service'); ?>">
                        <a href="#">
                            <i class="fa fa-credit-card ftlayer"></i> <span><?php echo $this->lang->line('academics'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <?php if ($this->rbac->hasPrivilege('class_timetable', 'can_view')) {?>
                               <!-- <li class="<?php echo set_Submenu('Academics/timetable'); ?>"><a href="<?php echo base_url(); ?>admin/timetable/classreport"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('class_timetable'); ?></a></li>-->
                                <?php
}

       /* if ($this->rbac->hasPrivilege('teachers_time_table', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Academics/timetable/mytimetable'); ?>"><a href="<?php echo base_url(); ?>admin/timetable/mytimetable"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('teachers') . " " . $this->lang->line('timetable') ?></a></li>
                                <?php
}*/

        /*if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/teacher/assign_class_teacher'); ?>"><a href="<?php echo base_url(); ?>admin/teacher/assign_class_teacher"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('assign_class_teacher'); ?></a></li>
                                <?php
}*/

        /*if ($this->rbac->hasPrivilege('promote_student', 'can_view')) {
            ?>

                                <li class="<?php echo set_Submenu('stdtransfer/index'); ?>"><a href="<?php echo base_url(); ?>admin/stdtransfer"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('promote_students'); ?></a></li>
                                <?php
}*/
        /*if ($this->rbac->hasPrivilege('subject_group', 'can_view')) {
            ?>
                              <  <li class="<?php echo set_Submenu('subjectgroup/index'); ?>"><a href="<?php echo base_url('admin/subjectgroup'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('subject') . " " . $this->lang->line('group') ?></a></li>
                                <?php
}*/
        /*if ($this->rbac->hasPrivilege('subject', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Academics/projet'); ?>"><a href="<?php echo base_url(); ?>admin/projet"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('subjects'); ?></a></li>
                                <?php
}*/
                            /*if ($this->rbac->hasPrivilege('projet', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/projet'); ?>"><a href="<?php echo base_url(); ?>admin/projet"><i class="fa fa-angle-double-right"></i>Gestion de projets</a></li>

                                <?php
                            }*/
                            if ($this->rbac->hasPrivilege('projet', 'can_view')) {
                                ?>

                                <li class="<?php echo set_Submenu('admin/projects'); ?>"><a href="<?php echo base_url(); ?>admin/projects"><i class="fa fa-angle-double-right"></i>Gestions de projet </a></li>
                                <?php
                            }
                            if ($this->rbac->hasPrivilege('projet', 'can_view')) {
                                ?>

                                <li class="<?php echo set_Submenu('admin/follow'); ?>"><a href="<?php echo base_url(); ?>admin/follow"><i class="fa fa-angle-double-right"></i>Suivie de projet </a></li>
                                <?php
                            }



        /*if ($this->rbac->hasPrivilege('class', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('classes/index'); ?>"><a href="<?php echo base_url(); ?>classes"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('class'); ?></a></li>
                                <?php
}*/

        /*if ($this->rbac->hasPrivilege('section', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('fichiers/index'); ?>"><a href="<?php echo base_url(); ?>fichiers"><i class="fa fa-download ftlayer"></i> <?php echo $this->lang->line('sections'); ?></a></li>
                                <?php
}*/
                            if ($this->rbac->hasPrivilege('file', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/files'); ?>"><a href="<?php echo base_url(); ?>admin/files"><i class="fa fa-download ftlayer"></i>Gestion des fichiers</a></li>

                                <?php
                            }


        ?>



                        </ul>
                    </li>
                    <?php
}
}
?>


            <?php


            if ($this->module_lib->hasActive('human_resource')) {
                if (($this->rbac->hasPrivilege('staff', 'can_view') ||
                    $this->rbac->hasPrivilege('approve_leave_request', 'can_view') ||
                    $this->rbac->hasPrivilege('apply_leave', 'can_view') ||
                    $this->rbac->hasPrivilege('leave_types', 'can_view') ||
                    $this->rbac->hasPrivilege('recrutement', 'can_view') ||
                    $this->rbac->hasPrivilege('categorie_salaire', 'can_view') ||
                    $this->rbac->hasPrivilege('training', 'can_view') ||
                    $this->rbac->hasPrivilege('formations', 'can_view') ||
                    $this->rbac->hasPrivilege('admission_enquiry', 'can_view') ||
                    $this->rbac->hasPrivilege('teachers_rating', 'can_view') ||
                    $this->rbac->hasPrivilege('department', 'can_view') ||
                    $this->rbac->hasPrivilege('designation', 'can_view') ||
                    $this->rbac->hasPrivilege('sanction', 'can_view') ||
                    $this->rbac->hasPrivilege('disable_staff', 'can_view'))) {
                    ?>
                    <li class="treeview <?php echo set_Topmenu('HR'); ?>">
                        <a href="#">
                            <i class="fa fa-sitemap ftlayer"></i> <span><?php echo $this->lang->line('human_resource'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('HR/staff'); ?>"><a href="<?php echo base_url(); ?>admin/staff"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('staff_directory'); ?></a></li>

                            <?php }
                            ?>

                            <?php
                            if ($this->rbac->hasPrivilege('staff_attendance', 'can_view')) {
                                ?>
                               <li class="<?php echo set_Submenu('admin/staffattendance'); ?>"><a href="<?php echo base_url(); ?>admin/staffattendance"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('staff_attendance'); ?></a></li>
                                <?php
                            }

                            if ($this->rbac->hasPrivilege('staff_payroll', 'can_view')) {
                                ?>


                                <li class="<?php echo set_Submenu('admin/payroll'); ?>"><a href="<?php echo base_url(); ?>admin/payroll"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('payroll'); ?></a></li>
                                <?php
                            }

                            if ($this->rbac->hasPrivilege('approve_leave_request', 'can_view')) {
                                ?>
                                <a href="#">
                                    <i style="color: white; margin-top: 10px;margin-left: 13px" class="fa fa-angle-double-right"></i> <span style="color: white;margin: 13px">Gestion des congés</span> <i  style="margin-top: 10px;color: white" class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('admin/leaverequest/leaverequest'); ?>"><a href="<?php echo base_url(); ?>admin/leaverequest/leaverequest"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('approve_leave_request'); ?></a></li>
                                    <li class="<?php echo set_Submenu('admin/leavetypes'); ?>"><a href="<?php echo base_url(); ?>admin/leavetypes"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('leave_type'); ?></a></li>
                                    <li class="<?php echo set_Submenu('admin/staff/leaverequest'); ?>"><a href="<?php echo base_url(); ?>admin/staff/leaverequest"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('apply_leave'); ?></a></li>

                                </ul>

                                <!-- <li class="<?php echo set_Submenu('admin/leaverequest/leaverequest'); ?>"><a href="<?php echo base_url(); ?>admin/leaverequest/leaverequest"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('approve_leave_request'); ?></a></li>
-->
                                <?php
                            }
                            if ($this->rbac->hasPrivilege('apply_leave', 'can_view')) {
                                ?>
                               <!-- <li class="<?php echo set_Submenu('admin/staff/leaverequest'); ?>"><a href="<?php echo base_url(); ?>admin/staff/leaverequest"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('apply_leave'); ?></a></li>-->
                                <?php
                            }


                            if ($this->rbac->hasPrivilege('leave_types', 'can_view')) {
                                ?>

                              <!--  <li class="<?php echo set_Submenu('admin/leavetypes'); ?>"><a href="<?php echo base_url(); ?>admin/leavetypes"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('leave_type'); ?></a></li>
-->
                                <?php
                            }
                            if ($this->rbac->hasPrivilege('training', 'can_view')) {
                                ?>

                                <!--<li class="<?php echo set_Submenu('admin/trainings'); ?>"><a href="<?php echo base_url(); ?>admin/trainings"><i class="fa fa-angle-double-right"></i> Formations</a></li>-->

                                <?php
                            }
                               if ($this->rbac->hasPrivilege('formations', 'can_view')) {?>
                            <li class="<?php echo set_Submenu('book/getall'); ?>">
                                <a href="<?php echo base_url(); ?>admin/training/getall"><i class="fa fa-angle-double-right"></i>Formations</a></li>
                            <?php }

                            if ($this->rbac->hasPrivilege('recrutement', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/recrutement'); ?>"><a href="<?php echo base_url(); ?>admin/recrutement"><i class="fa fa-angle-double-right"></i>Recrutement</a></li>

                                <?php
                            }

                            if ($this->rbac->hasPrivilege('categorie_salaire', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/categorie'); ?>"><a href="<?php echo base_url(); ?>admin/categorie"><i class="fa fa-angle-double-right"></i>Catégorie salariale</a></li>

                                <?php
                            }

                            if ($this->rbac->hasPrivilege('admission_enquiry', 'can_view')) {
                                ?>

                                <li class="<?php echo set_Submenu('admin/enquiry'); ?>"><a href="<?php echo base_url(); ?>admin/enquiry"><i class="fa fa-angle-double-right"></i>Permissions/Demissions </a></li>
                                <?php
                            }




                            if ($this->rbac->hasPrivilege('teachers_rating', 'can_view')) {
                                ?>
                                <!--<li class="<?php echo set_Submenu('HR/rating'); ?>"><a href="<?php echo base_url(); ?>admin/staff/rating"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('teachers') . " " . $this->lang->line('rating'); ?></a></li>-->
                                <?php
                            }

                            if ($this->rbac->hasPrivilege('department', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/department/department'); ?>"><a href="<?php echo base_url(); ?>admin/department/department"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('department'); ?></a></li>

                                <?php
                            }
                            if ($this->rbac->hasPrivilege('designation', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/designation/designation'); ?>"><a href="<?php echo base_url(); ?>admin/designation/designation"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('designation'); ?></a></li>
                                <?php
                            }

                            if ($this->rbac->hasPrivilege('sanction', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('admin/sanction/sanction'); ?>"><a href="<?php echo base_url(); ?>admin/sanction/sanction"><i class="fa fa-angle-double-right"></i> Sanction disciplinaire</a></li>
                                <?php
                            }

                            if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) {
                                ?>

                                <li class="<?php echo set_Submenu('HR/staff/disablestafflist'); ?>"><a href="<?php echo base_url(); ?>admin/staff/disablestafflist"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('disabled_staff'); ?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <?php
                }
            }
if ($this->module_lib->hasActive('communications')) {
    if (($this->rbac->hasPrivilege('panneau_notifications', 'can_view') ||
        $this->rbac->hasPrivilege('email', 'can_view') ||
        $this->rbac->hasPrivilege('sms', 'can_view') ||
        $this->rbac->hasPrivilege('email_log', 'can_view'))) {
        ?>
                    <li class = "treeview <?php echo set_Topmenu('Communications'); ?>">
                        <a href = "#">
                            <i class="fa fa-bullhorn ftlayer"></i> <span><?php echo $this->lang->line('communicate');
        ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <?php
if ($this->rbac->hasPrivilege('panneau_notifications', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('notification/index'); ?>"><a href="<?php echo base_url(); ?>admin/notification"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('notice_board'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('email', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Communicate/mailsms/compose'); ?>"><a href="<?php echo base_url(); ?>admin/mailsms/compose"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('send') . " " . $this->lang->line('email') ?></a></li>
                                <?php
}
        /*if ($this->rbac->hasPrivilege('sms', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('mailsms/compose_sms'); ?>"><a href="<?php echo base_url(); ?>admin/mailsms/compose_sms"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('send') . " " . $this->lang->line('sms') ?></a></li>
                                <?php
}*/
        if ($this->rbac->hasPrivilege('email_log', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('mailsms/index'); ?>"><a href="<?php echo base_url(); ?>admin/mailsms/index"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('email_/_sms_log'); ?></a></li>
                    <?php }?>

							<!--<li class="<?php echo set_Submenu('bulk_mail'); ?>"><a href="<?php echo base_url(); ?>student/bulkmail"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('login_credentials_send'); ?></a></li>-->

                        </ul>
                    </li>
                    <?php
}
}
?>

            <?php
if ($this->module_lib->hasModule('online_course')) {
    if ($this->module_lib->hasActive('online_course')) {
        if (($this->rbac->hasPrivilege('online_course', 'can_view')) ||
            ($this->rbac->hasPrivilege('
online_course_offline_payment', 'can_view')) ||
            ($this->rbac->hasPrivilege('student_course_purchase_report', 'can_view')) ||
            ($this->rbac->hasPrivilege('course_sell_count_report', 'can_view')) ||
            ($this->rbac->hasPrivilege('online_course_setting', 'can_view'))) {
            ?>
            <li class="treeview <?php echo set_Topmenu('onlinecourse'); ?>">
                <a href="#">
                    <i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('online_course'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php if ($this->rbac->hasPrivilege('online_course', 'can_view')  && $this->auth->addonchk('ssoclc',false)) {?>
                        <li class="<?php echo set_Submenu('onlinecourse/course/index'); ?>"><a href="<?php echo base_url(); ?>onlinecourse/course/index"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('online_course') ?></a></li>
                    <?php }?>
                    <?php if ($this->rbac->hasPrivilege('online_course_offline_payment', 'can_view') && $this->auth->addonchk('ssoclc',false)) {?>
                        <li class="<?php echo set_Submenu('onlinecourse/offlinepayment/index'); ?>"><a href="<?php echo base_url(); ?>onlinecourse/offlinepayment"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('offline_payment') ?></a></li>
                    <?php }?>
                    <?php if (($this->rbac->hasPrivilege('student_course_purchase_report', 'can_view')) ||
                ($this->rbac->hasPrivilege('course_sell_count_report', 'can_view')) ||
                ($this->rbac->hasPrivilege('course_trending_report', 'can_view')) ||
                ($this->rbac->hasPrivilege('course_complete_report', 'can_view'))) {
                    if($this->auth->addonchk('ssoclc',false)){?>
                        <li class="<?php echo set_Submenu('onlinecourse/coursereport/report'); ?>"><a href="<?php echo base_url(); ?>onlinecourse/coursereport/report"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('online_course_report') ?></a></li>
                    <?php } }?>
                    <?php if ($this->rbac->hasPrivilege('online_course_setting', 'can_view')) {?>
                        <li class="<?php echo set_Submenu('onlinecourse/course/setting'); ?>"><a href="<?php echo base_url(); ?>onlinecourse/course/setting"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('setting') ?></a></li>
                    <?php }?>
                </ul>
            </li>
            <?php }}}?>


            <?php
            if ($this->module_lib->hasModule('zoom_live_classes')) {
                if ($this->module_lib->hasActive('zoom_live_classes')) {
                    if (($this->rbac->hasPrivilege('setting', 'can_view')) || ($this->rbac->hasPrivilege('live_classes', 'can_view')) || ($this->rbac->hasPrivilege('live_meeting', 'can_view'))) {
                        ?>
                        <li class="treeview <?= set_Topmenu('conference'); ?>">
                            <a href="#">
                                <i class="fa fa-video-camera ftlayer"></i> <span>Réunions Zoom</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">


                                <?php if ($this->rbac->hasPrivilege('live_classes', 'can_view')) {
                                    ?>
                                  <!--  <li class="<?= set_Submenu('conference/live_class'); ?>"><a href="<?= base_url('admin/conference/timetable'); ?>"><i class="fa fa-angle-double-right"></i>Cours </a></li>-->
                                <?php } ?>
                                <?php if ($this->rbac->hasPrivilege('live_meeting', 'can_view')) {
                                    ?>
                                    <li class="<?= set_Submenu('conference/live_meeting'); ?>"><a href="<?= base_url('admin/conference/meeting'); ?>"><i class="fa fa-angle-double-right"></i>Réunions</a></li>
                                <?php } ?>
                                <?php if ($this->rbac->hasPrivilege('live_classes_report', 'can_view')) {
                                    ?>
                                   <!-- <li class="<?= set_Submenu('conference/class_report'); ?>"><a href="<?= base_url('admin/conference/class_report'); ?>"><i class="fa fa-angle-double-right"></i> Etats Cours</a></li>-->
                                <?php }
                                if ($this->rbac->hasPrivilege('live_meeting_report', 'can_view')) {
                                    ?>
                                    <!--<li class="<?= set_Submenu('conference/meeting_report'); ?>"><a href="<?= base_url('admin/conference/meeting_report'); ?>"><i class="fa fa-angle-double-right"></i>Etats Réunions</a></li>-->
                                <?php } ?>
                                <?php if ($this->rbac->hasPrivilege('setting', 'can_view')) {
                                    ?>
                                    <li class="<?= set_Submenu('conference/zoom_api_setting'); ?>"><a href="<?= base_url('admin/conference'); ?>"><i class="fa fa-angle-double-right"></i>Configuration</a></li>
                                <?php }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
            }
            ?>

            <?php
if ($this->module_lib->hasModule('gmeet_live_classes')) {
    if ($this->module_lib->hasActive('gmeet_live_classes')) {
        if (($this->rbac->hasPrivilege('gmeet_live_classes', 'can_view')) || ($this->rbac->hasPrivilege('gmeet_live_meeting', 'can_view')) || ($this->rbac->hasPrivilege('gmeet_live_meeting_report', 'can_view')) || ($this->rbac->hasPrivilege('gmeet_live_classes_report', 'can_view'))) {
            ?>
                        <li class="treeview <?php echo set_Topmenu('gmeet'); ?>">
                            <a href="#">
                                <i class="fa fa-video-camera ftlayer"></i> <span> Réunion en ligne Gmeet</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <?php if ($this->rbac->hasPrivilege('gmeet_live_classes', 'can_view') && $this->auth->addonchk('ssglc', false)) {?>
                                    <li class="<?php echo set_Submenu('gmeet/live_class'); ?>"><a href="<?php echo site_url('admin/gmeet/timetable'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('live_class') ?></a></li>
                                <?php }if ($this->rbac->hasPrivilege('gmeet_live_meeting', 'can_view') && $this->auth->addonchk('ssglc', false)) {?>
                                    <li class="<?php echo set_Submenu('gmeet/live_meeting'); ?>"><a href="<?php echo site_url('admin/gmeet/meeting'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('live_meeting') ?></a></li>
                                <?php }if ($this->rbac->hasPrivilege('gmeet_live_classes_report', 'can_view') && $this->auth->addonchk('ssglc', false)) {?>
                                    <li class="<?php echo set_Submenu('gmeet/class_report'); ?>"><a href="<?php echo site_url('admin/gmeet/class_report'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('live_class') . " " . $this->lang->line('report'); ?></a></li>
                                <?php }if ($this->rbac->hasPrivilege('gmeet_live_meeting_report', 'can_view') && $this->auth->addonchk('ssglc', false)) {?>
                                    <li class="<?php echo set_Submenu('gmeet/meeting_report'); ?>"><a href="<?php echo site_url('admin/gmeet/meeting_report'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('live_meeting') . " " . $this->lang->line('report') ?></a></li>
            <?php }if ($this->rbac->hasPrivilege('gmeet_setting', 'can_view')) {?>
            <li class="<?php echo set_Submenu('gmeet/gmeet_setting'); ?>"><a href="<?php echo base_url('admin/gmeet/index'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('setting') ?></a></li>
        <?php }?>
                            </ul>
                        </li>


                    <?php
}
    }
}
?>

<?php
if ($this->module_lib->hasActive('download_center')) {
    if (($this->rbac->hasPrivilege('upload_content', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Download Center'); ?>">
                        <a href="#">
                            <i class="fa fa-download ftlayer"></i> <span><?php echo $this->lang->line('download_center'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
        <?php if ($this->rbac->hasPrivilege('upload_content', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/content'); ?>"><a href="<?php echo base_url(); ?>admin/content"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('upload_content'); ?></a></li>
        <?php }?>
                            <li class="<?php echo set_Submenu('content/assignment'); ?>"><a href="<?php echo base_url(); ?>admin/content/assignment"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('assignments'); ?></a></li>
                            <li class="<?php echo set_Submenu('content/studymaterial'); ?>"><a href="<?php echo base_url(); ?>admin/content/studymaterial"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('study_material'); ?></a></li>
                            <li class="<?php echo set_Submenu('content/syllabus'); ?>"><a href="<?php echo base_url(); ?>admin/content/syllabus"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('syllabus'); ?></a></li>
                            <li class="<?php echo set_Submenu('content/other'); ?>"><a href="<?php echo base_url(); ?>admin/content/other"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('other_downloads'); ?></a></li>
                        </ul>
                    </li>
                    <?php
}
}

if ($this->module_lib->hasActive('homework')) {
    if (($this->rbac->hasPrivilege('homework', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Homework'); ?>">
                        <a href="#">
                            <i class="fa fa-flask ftlayer"></i> <span><?php echo $this->lang->line('homework'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                    <?php if ($this->rbac->hasPrivilege('homework', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('homework'); ?>"><a href="<?php echo base_url(); ?>homework"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('add_homework'); ?></a></li>
                    <?php }?>
                        </ul>
                    </li>
                    <?php
}
}
/*if ($this->module_lib->hasActive('formations')) {
    if (($this->rbac->hasPrivilege('formations', 'can_view') ||
        $this->rbac->hasPrivilege('issue_return', 'can_view') ||
        $this->rbac->hasPrivilege('add_staff_member', 'can_view') ||
        $this->rbac->hasPrivilege('add_student', 'can_view')
    )) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Formations'); ?>">
                        <a href="#">
                            <i class="fa fa-book ftlayer"></i> <span>Gestions des formations</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">


                            <?php if ($this->rbac->hasPrivilege('formations', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('book/getall'); ?>">
                                    <a href="<?php echo base_url(); ?>admin/training/getall"><i class="fa fa-angle-double-right"></i>Formations</a></li>
                            <?php }if ($this->rbac->hasPrivilege('issue_return', 'can_view')) {?>
                               <!-- <li class="<?php echo set_Submenu('member/index'); ?>"><a href="<?php echo base_url(); ?>admin/member"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('issue_return'); ?></a></li>-->
                            <?php }?>
                            <?php if ($this->rbac->hasPrivilege('add_student', 'can_view')) {?>
                               <!-- <li class="<?php echo set_Submenu('member/student'); ?>"><a href="<?php echo base_url(); ?>admin/member/student"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('add_student'); ?></a></li>-->
        <?php }?>
        <?php if ($this->rbac->hasPrivilege('add_staff_member', 'can_view')) {?>
                               <!-- <li class="<?php echo set_Submenu('Library/member/teacher'); ?>"><a href="<?php echo base_url(); ?>admin/member/teacher"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('add_staff_member'); ?></a></li>-->
        <?php }?>



                        </ul>
                    </li>
                    <?php
}
}*/

if ($this->module_lib->hasActive('commerciale')) {
    if (($this->rbac->hasPrivilege('vente', 'can_view') ||
        $this->rbac->hasPrivilege('item_stock', 'can_view') ||
        $this->rbac->hasPrivilege('commande', 'can_view') ||
        $this->rbac->hasPrivilege('item_category', 'can_view') ||
        $this->rbac->hasPrivilege('item_category', 'can_view') ||
        $this->rbac->hasPrivilege('store', 'can_view') ||
        $this->rbac->hasPrivilege('supplier', 'can_view'))) {
        ?>
        <li class="treeview <?php echo set_Topmenu('Commerciale'); ?>">
            <a href="#">
                <i class="fa fa-credit-card ftlayer"></i> <span>Commerciale</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php if ($this->rbac->hasPrivilege('vente', 'can_view')) {?>
                 <li class="<?php echo set_Submenu('ventes/index'); ?>"><a href="<?php echo base_url(); ?>admin/ventes"><i class="fa fa-angle-double-right"></i>Ventes</a></li>
                    <?php
                }

                if ($this->rbac->hasPrivilege('commande', 'can_view')) {
                    ?>
                    <li class="<?php echo set_Submenu('commande/index'); ?>"><a href="<?php echo base_url(); ?>admin/commande"><i class="fa fa-angle-double-right"></i>Bon de commande</a></li>
                    <?php
                }
                if ($this->rbac->hasPrivilege('devis', 'can_view')) {
                    ?>
                    <li class="<?php echo set_Submenu('devis/index'); ?>"><a href="<?php echo base_url(); ?>admin/devis"><i class="fa fa-angle-double-right"></i>Devis / Proforma</a></li>
                    <?php
                }
                if ($this->rbac->hasPrivilege('clients', 'can_view')) {
                    ?>
                    <li class="<?php echo set_Submenu('clients/index'); ?>"><a href="<?php echo base_url(); ?>admin/clients"><i class="fa fa-angle-double-right"></i>Clients</a></li>
                <?php }?>
            </ul>
        </li>
        <?php
    }
}

if ($this->module_lib->hasActive('inventory')) {
    if (($this->rbac->hasPrivilege('issue_item', 'can_view') ||
        $this->rbac->hasPrivilege('item_stock', 'can_view') ||
        $this->rbac->hasPrivilege('item', 'can_view') ||
        $this->rbac->hasPrivilege('item_category', 'can_view') ||
        $this->rbac->hasPrivilege('item_category', 'can_view') ||
        $this->rbac->hasPrivilege('store', 'can_view') ||
        $this->rbac->hasPrivilege('supplier', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Inventory'); ?>">
                        <a href="#">
                            <i class="fa fa-object-group ftlayer"></i> <span>Gestion commerciale</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('issue_item', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('issueitem/index'); ?>"><a href="<?php echo base_url(); ?>admin/issueitem"><i class="fa fa-angle-double-right"></i>Ventes</a></li>
                                <li class="<?php echo set_Submenu('issueitem/index'); ?>"><a href="<?php echo base_url(); ?>admin/issueitem"><i class="fa fa-angle-double-right"></i>Devis</a></li>
                                <li class="<?php echo set_Submenu('delivery/index'); ?>"><a href="<?php echo base_url(); ?>admin/delivery"><i class="fa fa-angle-double-right"></i>Bon de livraison</a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('item_stock', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Itemstock/index'); ?>"><a href="<?php echo base_url(); ?>admin/itemstock"><i class="fa fa-angle-double-right"></i>Achats</a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('item', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('Item/index'); ?>"><a href="<?php echo base_url(); ?>admin/item"><i class="fa fa-angle-double-right"></i>Liste des produits</a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('item_category', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('itemcategory/index'); ?>"><a href="<?php echo base_url(); ?>admin/itemcategory"><i class="fa fa-angle-double-right"></i>Catégorie de produit</a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('store', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('itemstore/index'); ?>"><a href="<?php echo base_url(); ?>admin/itemstore"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('item_store'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('supplier', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('itemsupplier/index'); ?>"><a href="<?php echo base_url(); ?>admin/itemsupplier"><i class="fa fa-angle-double-right"></i>Fournisseurs</a></li>
                    <?php }
                            if ($this->rbac->hasPrivilege('clients', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('clients/index'); ?>"><a href="<?php echo base_url(); ?>admin/clients"><i class="fa fa-angle-double-right"></i>Clients</a></li>
                            <?php }
        ?>
                        </ul>
                    </li>
                    <?php
}
}
if ($this->module_lib->hasActive('transport')) {
    if (($this->rbac->hasPrivilege('routes', 'can_view') ||
        $this->rbac->hasPrivilege('vehicle', 'can_view') ||
        $this->rbac->hasPrivilege('assign_vehicle', 'can_view') ||
        $this->rbac->hasPrivilege('assign_vehicle', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Transport'); ?>">
                        <a href="#">
                            <i class="fa fa-bus ftlayer"></i> <span><?php echo $this->lang->line('transport'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('routes', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('route/index'); ?>"><a href="<?php echo base_url(); ?>admin/route"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('routes'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('vehicle', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('vehicle/index'); ?>"><a href="<?php echo base_url(); ?>admin/vehicle"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('vehicles'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('assign_vehicle', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('vehroute/index'); ?>"><a href="<?php echo base_url(); ?>admin/vehroute"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('assign_vehicle'); ?></a></li>
            <?php
}
        ?>
                        </ul>
                    </li>
                    <?php
}
}
if ($this->module_lib->hasActive('hostel')) {
    if (($this->rbac->hasPrivilege('hostel_rooms', 'can_view') ||
        $this->rbac->hasPrivilege('room_type', 'can_view') ||
        $this->rbac->hasPrivilege('hostel', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Hostel'); ?>">
                        <a href="#">
                            <i class="fa fa-building-o ftlayer"></i> <span><?php echo $this->lang->line('hostel'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('hostel_rooms', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('hostelroom/index'); ?>"><a href="<?php echo base_url(); ?>admin/hostelroom"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('hostel_rooms'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('room_type', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('roomtype/index'); ?>"><a href="<?php echo base_url(); ?>admin/roomtype"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('room_type'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('hostel', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('hostel/index'); ?>"><a href="<?php echo base_url(); ?>admin/hostel"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('hostel'); ?></a></li>
            <?php
}
        ?>
                        </ul>
                    </li>
                    <?php
}
}
if ($this->module_lib->hasActive('certificate')) {
    if (($this->rbac->hasPrivilege('student_certificate', 'can_view') ||
        $this->rbac->hasPrivilege('generate_certificate', 'can_view') ||
        $this->rbac->hasPrivilege('student_id_card', 'can_view') ||
        $this->rbac->hasPrivilege('generate_id_card', 'can_view') ||
        $this->rbac->hasPrivilege('staff_id_card', 'can_view') ||
        $this->rbac->hasPrivilege('generate_staff_id_card', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Certificate'); ?>">
                        <a href="#">
                            <i class="fa fa-newspaper-o ftlayer"></i> <span><?php echo $this->lang->line('certificate'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('student_certificate', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('admin/certificate'); ?>"><a href="<?php echo base_url(); ?>admin/certificate/"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('certificate'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('generate_certificate', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('admin/generatecertificate'); ?>"><a href="<?php echo base_url(); ?>admin/generatecertificate/"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('generate'); ?> <?php echo $this->lang->line('certificate'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('student_id_card', 'can_view')) {
            ?>
                                <!--<li class="<?php echo set_Submenu('admin/studentidcard'); ?>"><a href="<?php echo base_url('admin/studentidcard/'); ?>"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('icard'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('generate_id_card', 'can_view')) {
            ?>
                           <!--     <li class="<?php echo set_Submenu('admin/generateidcard'); ?>"><a href="<?php echo base_url('admin/generateidcard/search'); ?>"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('generate'); ?> <?php echo $this->lang->line('icard'); ?></a></li>-->
                            <?php
}
        if ($this->rbac->hasPrivilege('staff_id_card', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/staffcertificate'); ?>"><a href="<?php echo base_url('admin/staffcertificate/'); ?>"><i class="fa fa-angle-double-right"></i>Produire un certificat</a></li>
                                  <li class="<?php echo set_Submenu('admin/staffcertificatelist'); ?>"><a href="<?php echo base_url('admin/staffcertificatelist/'); ?>"><i class="fa fa-angle-double-right"></i>Base de donnée</a></li>

            <?php
}
        if ($this->rbac->hasPrivilege('generate_staff_id_card', 'can_view')) {
            ?>
                                        <li class="<?php echo set_Submenu('admin/generatestaffcertificate'); ?>"><a href="<?php echo base_url('admin/generatestaffcertificate/'); ?>"><i class="fa fa-angle-double-right"></i>Générer un certificat</a></li>

        <?php }?>
                        </ul>
                    </li>
                    <?php
}
}
if ($this->module_lib->hasActive('front_cms')) {
    if (($this->rbac->hasPrivilege('event', 'can_view') ||
        $this->rbac->hasPrivilege('gallery', 'can_view') ||
        $this->rbac->hasPrivilege('notice', 'can_view') ||
        $this->rbac->hasPrivilege('media_manager', 'can_view') ||
        $this->rbac->hasPrivilege('pages', 'can_view') ||
        $this->rbac->hasPrivilege('menus', 'can_view') ||
        $this->rbac->hasPrivilege('banner_images', 'can_view'))) {
        ?>
                    <li class="treeview <?php echo set_Topmenu('Front CMS'); ?>">
                        <a href="#">
                            <i class="fa fa-empire ftlayer"></i> <span><?php echo $this->lang->line('front_cms'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('event', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('admin/front/events'); ?>"><a href="<?php echo base_url(); ?>admin/front/events"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('event'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('gallery', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/gallery'); ?>"><a href="<?php echo base_url(); ?>admin/front/gallery"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('gallery'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('notice', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/notice'); ?>"><a href="<?php echo base_url(); ?>admin/front/notice"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('notice'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('media_manager', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/media'); ?>"><a href="<?php echo base_url(); ?>admin/front/media"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('media_manager'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('pages', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/page'); ?>"><a href="<?php echo base_url(); ?>admin/front/page"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('page'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('menus', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/menus'); ?>"><a href="<?php echo base_url(); ?>admin/front/menus"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('menus'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('banner_images', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('admin/front/banner'); ?>"><a href="<?php echo base_url(); ?>admin/front/banner"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('banner_images'); ?></a></li>
                    <?php }?>
                        </ul>
                    </li>
                    <?php
}
}
?>
<?php
if ($this->module_lib->hasActive('alumni')) {
    if (($this->rbac->hasPrivilege('manage_alumni', 'can_view')) || ($this->rbac->hasPrivilege('events', 'can_view'))) {
        ?>


                    <li class="treeview <?php echo set_Topmenu('alumni'); ?>">
                        <a href="#">
                            <i class="fa fa-universal-access ftlayer"></i> <span><?php echo $this->lang->line('alumni'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($this->rbac->hasPrivilege('manage_alumni', 'can_view')) {?>
                              <!--  <li class="<?php echo set_Submenu('alumni/alumnilist'); ?>"><a href="<?php echo base_url(); ?>admin/alumni/alumnilist"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('manage_alumini'); ?></a></li>-->
        <?php }?>
        <?php if ($this->rbac->hasPrivilege('events', 'can_view')) {?>
                                <li class="<?php echo set_Submenu('alumni/event'); ?>"><a href="<?php echo base_url(); ?>admin/alumni/events"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('events'); ?></a></li>
                    <?php }?>

                        </ul>
                    </li>
                <?php }
}
?>
            <?php
            if ($this->module_lib->hasActive('reports')) {
                if (($this->rbac->hasPrivilege('student_report', 'can_view') ||
                    $this->rbac->hasPrivilege('guardian_report', 'can_view') ||
                    $this->rbac->hasPrivilege('student_history', 'can_view') ||
                    $this->rbac->hasPrivilege('student_login_credential_report', 'can_view') ||
                    $this->rbac->hasPrivilege('class_subject_report', 'can_view') ||
                    $this->rbac->hasPrivilege('admission_report', 'can_view') ||
                    $this->rbac->hasPrivilege('sibling_report', 'can_view') ||
                    $this->rbac->hasPrivilege('evaluation_report', 'can_view') ||
                    $this->rbac->hasPrivilege('student_profile', 'can_view') ||
                    $this->rbac->hasPrivilege('fees_statement', 'can_view') ||
                    $this->rbac->hasPrivilege('balance_fees_report', 'can_view') ||
                    $this->rbac->hasPrivilege('fees_collection_report', 'can_view') ||
                    $this->rbac->hasPrivilege('online_fees_collection_report', 'can_view') ||
                    $this->rbac->hasPrivilege('income_report', 'can_view') ||
                    $this->rbac->hasPrivilege('expense_report', 'can_view') ||
                    $this->rbac->hasPrivilege('payroll_report', 'can_view') ||
                    $this->rbac->hasPrivilege('income_group_report', 'can_view') ||
                    $this->rbac->hasPrivilege('expense_group_report', 'can_view') ||
                    $this->rbac->hasPrivilege('attendance_report', 'can_view') ||
                    $this->rbac->hasPrivilege('staff_attendance_report', 'can_view') ||
                    $this->rbac->hasPrivilege('exam_marks_report', 'can_view') ||
                    $this->rbac->hasPrivilege('online_exam_wise_report', 'can_view') ||
                    $this->rbac->hasPrivilege('online_exams_report', 'can_view') ||
                    $this->rbac->hasPrivilege('online_exams_attempt_report', 'can_view') ||
                    $this->rbac->hasPrivilege('online_exams_rank_report', 'can_view') ||
                    $this->rbac->hasPrivilege('payroll_report', 'can_view') ||
                    $this->rbac->hasPrivilege('transport_report', 'can_view') ||
                    $this->rbac->hasPrivilege('hostel_report', 'can_view') ||
                    $this->rbac->hasPrivilege('audit_trail_report', 'can_view') ||
                    $this->rbac->hasPrivilege('user_log', 'can_view') ||
                    $this->rbac->hasPrivilege('book_issue_report', 'can_view') ||
                    $this->rbac->hasPrivilege('book_due_report', 'can_view') ||
                    $this->rbac->hasPrivilege('book_inventory_report', 'can_view') ||
                    $this->rbac->hasPrivilege('stock_report', 'can_view') ||
                    $this->rbac->hasPrivilege('add_item_report', 'can_view') ||
                    $this->rbac->hasPrivilege('issue_inventory_report', 'can_view') ||
                    $this->rbac->hasPrivilege('syllabus_status_report', 'can_view') ||
                    $this->rbac->hasPrivilege('teacher_syllabus_status_report', 'can_view'))) {
                    ?>
                    <li class="treeview <?php echo set_Topmenu('Reports'); ?>">
                        <a href="#">
                            <i class="fa fa-line-chart ftlayer"></i> <span><?php echo $this->lang->line('reports'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
                            if (($this->rbac->hasPrivilege('student_report', 'can_view') ||
                                $this->rbac->hasPrivilege('guardian_report', 'can_view') ||
                                $this->rbac->hasPrivilege('student_history', 'can_view') ||
                                $this->rbac->hasPrivilege('student_login_credential_report', 'can_view') ||
                                $this->rbac->hasPrivilege('class_subject_report', 'can_view') ||
                                $this->rbac->hasPrivilege('admission_report', 'can_view') ||
                                $this->rbac->hasPrivilege('sibling_report', 'can_view') ||
                                $this->rbac->hasPrivilege('evaluation_report', 'can_view') ||
                                $this->rbac->hasPrivilege('student_profile', 'can_view'))) {
                                ?>
                               <!-- <li class="<?php echo set_Submenu('Reports/student_information'); ?>"><a href="<?php echo base_url(); ?>report/studentinformation"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student_information'); ?></a></li>-->
                                <?php
                            }
                            if (($this->rbac->hasPrivilege('fees_statement', 'can_view') ||
                                $this->rbac->hasPrivilege('balance_fees_report', 'can_view') ||
                                $this->rbac->hasPrivilege('fees_collection_report', 'can_view') ||
                                $this->rbac->hasPrivilege('online_fees_collection_report', 'can_view') ||
                                $this->rbac->hasPrivilege('income_report', 'can_view') ||
                                $this->rbac->hasPrivilege('expense_report', 'can_view') ||
                                $this->rbac->hasPrivilege('payroll_report', 'can_view') ||
                                $this->rbac->hasPrivilege('income_group_report', 'can_view') ||
                                $this->rbac->hasPrivilege('expense_group_report', 'can_view'))) {
                                ?>
                                <li class="<?php echo set_Submenu('Reports/finance'); ?>"><a href="<?php echo base_url(); ?>report/finance"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('finance'); ?></a></li>
                                <?php
                            }if (($this->rbac->hasPrivilege('attendance_report', 'can_view') ||
                                $this->rbac->hasPrivilege('staff_attendance_report', 'can_view'))) {
                                ?>

                               <li class="<?php echo set_Submenu('Reports/attendance'); ?>"><a href="<?php echo base_url(); ?>report/attendance"><i class="fa fa-angle-double-right"></i>Rapport des absences</a></li>
                                <?php
                            }if (($this->rbac->hasPrivilege('rank_report', 'can_view'))) {
                                ?>
                                <!--<li class="<?php echo set_Submenu('Reports/examinations'); ?>"><a href="<?php echo base_url(); ?>report/examinations"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('examinations'); ?></a></li>-->
                                <?php
                            }
                            if ($this->module_lib->hasActive('online_examination')) {
                                if (($this->rbac->hasPrivilege('online_exam_wise_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('online_exams_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('online_exams_attempt_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('online_exams_rank_report', 'can_view')
                                )) {
                                    ?>
                                   <!-- <li class="<?php echo set_Submenu('Reports/online_examinations'); ?>"><a href="<?php echo base_url(); ?>admin/onlineexam/report"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('online') . " " . $this->lang->line('examinations'); ?></a></li>-->
                                    <?php
                                }
                            }

                            if ($this->module_lib->hasActive('lesson_plan')) {
                                if (($this->rbac->hasPrivilege('syllabus_status_report', 'can_view') || $this->rbac->hasPrivilege('teacher_syllabus_status_report', 'can_view'))) {
                                    ?>
                                   <!-- <li class="<?php echo set_Submenu('Reports/lesson_plan'); ?>"><a href="<?php echo base_url(); ?>report/lesson_plan"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('lesson_plan'); ?></a></li>-->
                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('human_resource')) {
                                if (($this->rbac->hasPrivilege('staff_report', 'can_view') || $this->rbac->hasPrivilege('payroll_report', 'can_view'))) {
                                    ?>

                                    <li class="<?php echo set_Submenu('Reports/human_resource'); ?>"><a href="<?php echo base_url(); ?>report/staff_report"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('human_resource'); ?></a></li>

                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('library')) {
                                if (($this->rbac->hasPrivilege('book_issue_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('book_due_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('book_inventory_report', 'can_view'))) {
                                    ?>
                                  <!--  <li class="<?php echo set_Submenu('Reports/library'); ?>"><a href="<?php echo base_url(); ?>report/library"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('library'); ?></a></li>-->
                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('inventory')) {
                                if ((
                                    $this->rbac->hasPrivilege('stock_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('add_item_report', 'can_view') ||
                                    $this->rbac->hasPrivilege('issue_inventory_report', 'can_view'))) {
                                    ?>
                                    <!--<li class="<?php echo set_Submenu('Reports/inventory'); ?>"><a href="<?php echo base_url(); ?>report/inventory"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('inventory'); ?></a></li>-->
                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('transport')) {
                                if ($this->rbac->hasPrivilege('transport_report', 'can_view')) {
                                    ?>
                                    <!--<li class="<?php echo set_Submenu('reports/studenttransportdetails'); ?>"><a href="<?php echo base_url(); ?>admin/route/studenttransportdetails"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('transport'); ?></a></li>-->
                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('hostel')) {
                                if ($this->rbac->hasPrivilege('hostel_report', 'can_view')) {
                                    ?>
                                    <li class="<?php echo set_Submenu('reports/studenthosteldetails'); ?>"><a href="<?php echo base_url(); ?>admin/hostelroom/studenthosteldetails"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('hostel'); ?></a></li>
                                    <?php
                                }
                            }
                            if ($this->module_lib->hasActive('alumni')) {
                                if ($this->rbac->hasPrivilege('alumni_report', 'can_view')) {
                                    ?>
                                    <li class="<?php echo set_Submenu('Reports/alumni_report'); ?>"><a href="<?php echo base_url(); ?>report/alumnireport"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('alumni'); ?></a></li>
                                    <?php
                                }
                            }
                            if ($this->rbac->hasPrivilege('user_log', 'can_view')) {
                                ?>
                              <!--  <li class="<?php echo set_Submenu('Reports/userlog'); ?>"><a href="<?php echo base_url(); ?>admin/userlog"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('user_log'); ?></a></li>-->
                                <?php
                            }
                            if ($this->rbac->hasPrivilege('audit_trail_report', 'can_view')) {
                                ?>
                                <li class="<?php echo set_Submenu('audit/index'); ?>"><a href="<?php echo base_url(); ?>admin/audit"><i class="fa fa-angle-double-right"></i>
                                        <?php echo $this->lang->line('audit'); ?></a></li>
                                <!--<li class="<?php echo set_Submenu('audit/index'); ?>"><a href="<?php echo base_url(); ?>admin/audit"><i class="fa fa-angle-double-right"></i>
                                        <?php echo $this->lang->line('audit') . " " . $this->lang->line('trail') . " " . $this->lang->line('report'); ?></a></li>-->
                                <?php
                            }
                            ?>


                        </ul>
                    </li>
                    <?php
                }
            }
if ($this->module_lib->hasActive('system_settings')) {
    if (($this->rbac->hasPrivilege('general_setting', 'can_edit') ||
        $this->rbac->hasPrivilege('session_setting', 'can_view') ||
        $this->rbac->hasPrivilege('notification_setting', 'can_edit') ||
        $this->rbac->hasPrivilege('sms_setting', 'can_edit') ||
        $this->rbac->hasPrivilege('email_setting', 'can_edit') ||
        $this->rbac->hasPrivilege('payment_methods', 'can_edit') ||
        $this->rbac->hasPrivilege('languages', 'can_view') ||
        $this->rbac->hasPrivilege('languages', 'can_add') ||
        $this->rbac->hasPrivilege('backup_restore', 'can_view') ||
        $this->rbac->hasPrivilege('front_cms_setting', 'can_edit'))) {
        ?>

                    <li class="treeview <?php echo set_Topmenu('System Settings'); ?>">
                        <a href="#">
                            <i class="fa fa-gears ftlayer"></i> <span><?php echo $this->lang->line('system_settings'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php
if ($this->rbac->hasPrivilege('general_setting', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('schsettings/index'); ?>"><a href="<?php echo base_url(); ?>schsettings"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('general_settings'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('session_setting', 'can_view')) {
            ?>
                              <!--  <li class="<?php echo set_Submenu('sessions/index'); ?>"><a href="<?php echo base_url(); ?>sessions"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('session_setting'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('notification_setting', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('notification/setting'); ?>"><a href="<?php echo base_url(); ?>admin/notification/setting"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('notification_setting'); ?></a></li>-->
                                <?php
}

        if ($this->rbac->hasPrivilege('sms_setting', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('smsconfig/index'); ?>"><a href="<?php echo base_url(); ?>smsconfig"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('sms_setting'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('email_setting', 'can_view')) {
            ?>
                                <li class="<?php echo set_Submenu('emailconfig/index'); ?>"><a href="<?php echo base_url(); ?>emailconfig"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('email_setting'); ?></a></li>
                                <?php
}

        if ($this->rbac->hasPrivilege('payment_methods', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('admin/paymentsettings'); ?>"><a href="<?php echo base_url(); ?>admin/paymentsettings"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('payment_methods'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('print_header_footer', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('admin/print_headerfooter'); ?>"><a href="<?php echo base_url(); ?>admin/print_headerfooter"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('print_headerfooter'); ?></a></li>-->
                                <?php
}
        if ($this->module_lib->hasActive('front_cms')) {
            if ($this->rbac->hasPrivilege('front_cms_setting', 'can_view')) {
                ?>
                                   <!-- <li class="<?php echo set_Submenu('admin/frontcms/index'); ?>"><a href="<?php echo base_url(); ?>admin/frontcms"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('front_cms_setting'); ?></a></li>-->
                                    <?php
}
        }
        ?>
                            <?php if ($this->rbac->hasPrivilege('superadmin')) {?>
                               <li class="<?php echo set_Submenu('admin/roles'); ?>"><a href="<?php echo base_url(); ?>admin/roles"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('roles_permissions'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('backup', 'can_view')) {
            ?>
                                <!--<li class="<?php echo set_Submenu('admin/backup'); ?>"><a href="<?php echo base_url(); ?>admin/admin/backup"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('backup / restore'); ?></a></li>-->
                                <?php
}
        if ($this->rbac->hasPrivilege('languages', 'can_add')) {
            ?>
                               <li class="<?php echo set_Submenu('language/index'); ?>"><a href="<?php echo base_url(); ?>admin/language"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('languages'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('user_status')) {
            ?>
                                <li class="<?php echo set_Submenu('users/index'); ?>"><a href="<?php echo base_url(); ?>admin/users"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('users'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('superadmin')) {
            ?>
                              <li class="<?php echo set_Submenu('System Settings/module'); ?>"><a href="<?php echo base_url(); ?>admin/module"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('modules'); ?></a></li>
                                <?php
}
        if ($this->rbac->hasPrivilege('custom_fields', 'can_view')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('System Settings/customfield'); ?>"><a href="<?php echo base_url(); ?>admin/customfield"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('custom') . " " . $this->lang->line('fields'); ?></a></li>-->
                            <?php }
        if ($this->rbac->hasPrivilege('superadmin')) {
            ?>
                              <!--  <li class="<?php echo set_Submenu('System Settings/captcha'); ?>"><a href="<?php echo base_url(); ?>admin/captcha"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('captcha_setting'); ?></a></li>-->
                            <?php }
        if ($this->rbac->hasPrivilege('system_fields', 'can_view')) {
            ?>
                                <!--<li class="<?php echo set_Submenu('System Settings/systemfield'); ?>"><a href="<?php echo base_url(); ?>admin/systemfield"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('system') . " " . $this->lang->line('fields'); ?></a></li>-->
                                <?php
}if ($this->rbac->hasPrivilege('student_profile_update', 'can_view')) {
            ?>
                                <!--<li class="<?php echo set_Submenu('System Settings/profilesetting'); ?>"><a href="<?php echo base_url(); ?>student/profilesetting"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('student') . " " . $this->lang->line('profile') . " " . $this->lang->line('update'); ?></a></li>-->
                                    <?php
}

        ?>
                                <!--<li class="<?php echo set_Submenu('System Settings/onlineadmissionsetting'); ?>"><a href="<?php echo base_url(); ?>admin/onlineadmission/admissionsetting"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('online_admission_setting') ?></a></li>-->
                                    <?php

        if ($this->rbac->hasPrivilege('superadmin')) {
            ?>
                               <!-- <li class="<?php echo set_Submenu('System Settings/filetype'); ?>"><a href="<?php echo site_url('admin/admin/filetype'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('file_types'); ?></a></li>-->
                        <?php
}
        if ($this->rbac->hasPrivilege('superadmin')) {
            ?>
                            <!--    <li class="<?php// echo set_Submenu('System Settings/updater'); ?>"><a href="<?php// echo base_url(); ?>admin/updater"><i class="fa fa-angle-double-right"></i> <?php// echo $this->lang->line('system_update') ?></a></li>  -->
                        <?php
}

        ?>

                        </ul>
                    </li>
        <?php
}
}
?>
        </ul>
    </section>
</aside>