<?php

class KickBackAction extends HeraldCustomAction {

  public function appliesToAdapter(HeraldAdapter $adapter) {
    return ($adapter instanceof HeraldManiphestTaskAdapter);
  }

  public function appliesToRuleType($rule_type) {
    switch ($rule_type) {
      case HeraldRuleTypeConfig::RULE_TYPE_GLOBAL:
        return true;
      case HeraldRuleTypeConfig::RULE_TYPE_PERSONAL:
      case HeraldRuleTypeConfig::RULE_TYPE_OBJECT:
      default:
        return false;
    }
  }

  public function getActionKey() {
    return "swisspol.kick";
  }

  public function getActionName() {
    return pht('Reassign to author');
  }

  public function getActionType() {
    return HeraldAdapter::VALUE_NONE;
  }

  public function applyEffect(
    HeraldAdapter $adapter,
    $object,
    HeraldEffect $effect) {

    $task = $object;

    $xactions = array();

    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(ManiphestTransaction::TYPE_OWNER)
      ->setNewValue($original_author);

    foreach ($xactions as $xaction) {
      $adapter->queueTransaction($xaction);
    }

    return new HeraldApplyTranscript(
      $effect,
      true,
      pht('Reassigned to original author'));
  }
}
