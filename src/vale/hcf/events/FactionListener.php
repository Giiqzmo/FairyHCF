<?php
namespace vale\hcf\events;
use vale\hcf\HCF;
class FactionListener implements Listener{

  public HCF $plugin;
  
public function __construct(HCF $plugin){}
 $this->plugin = $plugin;
 $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
}
   
 public function FactionChat(){}
}