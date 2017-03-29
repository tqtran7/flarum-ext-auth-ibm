<?php

namespace Flarum\Auth\IBM;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ResourceOwner implements ResourceOwnerInterface {

  use ArrayAccessorTrait;
  
  protected $domain;
  protected $response;

  public function __construct(array $response = array()) {
      $this->response = $response;
  }

  public function getId() {
      return $this->getValueByKey($this->response, 'id');
  }

  public function getEmail() {
      return $this->getValueByKey($this->response, 'email');
  }

  public function getName() {
      return $this->getValueByKey($this->response, 'name');
  }

  public function getNickname() {
      return $this->getValueByKey($this->response, 'login');
  }

  public function getUrl() {
      $urlParts = array_filter([$this->domain, $this->getNickname()]);
      return count($urlParts) ? implode('/', $urlParts) : null;
  }

  public function setDomain($domain) {
      $this->domain = $domain;
      return $this;
  }

  public function toArray() {
      return $this->response;
  }
}
