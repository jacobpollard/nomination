<?php

  /**
   * WinnersView
   *
   * Administrative View for looking at winners for 
   * current and past nomination periods.
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('nomination', 'View.php');
PHPWS_Core::initCoreClass('DBPager.php');

class WinnersView extends OmNomView
{
    public function getRequestVars()
    {
        return array('view' => 'WinnersView');
    }

    public function display(Context $context)
    {
        if(!UserStatus::isAdmin()){
            throw new PermissionException('You are not allowed to see this!');
        }

        PHPWS_Core::initModClass('nomination', 'Nomination.php');
        PHPWS_Core::initModClass('nomination', 'Nominee.php');
        PHPWS_Core::initModClass('nomination', 'Nominator.php');

        /*$pager = new DBPager(NOMINATION_TABLE, 'Nomination');
        
        $pager->setModule('nomination');
        $pager->setTemplate('admin/winners.tpl');
        $pager->setEmptyMessage('No winners yet');

        $pager->addWhere('winner', 0, '!=');
        $pager->joinResult('nominee_id', NOMINEE_TABLE,
                           'id', 'last_name', 'nominee_last_name');
        $pager->joinResult('nominator_id', NOMINATOR_TABLE,
                           'id', 'last_name', 'nominator_last_name');
                           
        // Sort headers
        $pager->addSortHeader('period', 'Period');
        // Sort by last name
        $pager->addSortHeader('nominee_last_name', 'Nominee');
        $pager->addSortHeader('nominator_last_name', 'Nominator');
        */

        $pager = new DBpager('nomination_nomination', 'Nomination');
        $pager->setModule('nomination');
        $pager->setTemplate('admin/winners.tpl');
        $pager->setEmptyMessage('No Winners Yet');

        // conditions
        $pager->addWhere('winner', 0, '!=');
        //TODO: This'll work for testing. May need those joins still.

        // sort headers
        $pager->addSortHeader('period', 'Period');
        $pager->addSortHeader('last_name', 'Nominee');
        $pager->addSortHeader('nominator_last_name', 'Nominator');

        // Row tags
        $pager->addRowTags('rowTags');
        
        Layout::addPageTitle('View Winners');

        return $pager->get();
    }

}

?>
