<?php

namespace App\UserServices;

use GuzzleHttp\Client;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');
/**
 * Trait for Input Field validation
 */
trait FieldValidation {

   /**
   * @var array
   */
  private array $validInterests;

  /**
   * Function for the name field validation.
   * 
   * @param string $name
   *   Value of name fields.
   * 
   * @return bool
   *   Returns TRUE or FALSE based on validation.
   */
  private function nameValidation(string $name) {
    if ($name == "") {
      return FALSE;
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function for validating the phone number
   * 
   * @return bool
   */
  private function contactValidation() {
    if ($this->phone == '') {
      return FALSE;
    }
    elseif(strlen($this->phone)>13){
      return FALSE;
    }
    elseif(!preg_match("/[+][9][1][6-9][0-9]{9}/",$this->phone)){
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function for checking if the user has any interest
   */

  /**
   * Function to validate the correct passord formate.
   * 
   * @return bool
   */
  private function hasInterest() {
    $array = explode(',',$this->interest);
    if ($this->interest == '') {
      return FALSE;
    }
    elseif (count(array_intersect($array, $this->validInterests)) != count($array)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  private function validatePwd() {
    if ( $this->pwd == "") {
      return FALSE;
    }
    elseif(!preg_match("/[a-z]/",$this->pwd)) {
      return FALSE;
    }
    elseif(!preg_match("/[A-Z]/",$this->pwd)) {
      return FALSE;
    }
    elseif(!preg_match("/[0-9]/",$this->pwd)) {
      return FALSE;
    }
    elseif(!preg_match("/[@#$%&!]/",$this->pwd)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Function for trimming the data.
   * @param string $data
   * 
   * @return string
   */
  public function trimData(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  /**
   * Function for validating the email.
   * 
   * @return bool
   */
  public function validateEmail(){
    if ($this->email == '') {
      return FALSE;
    }
    elseif (!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function for checking wheher the password and
   * confirm password fields are same.
   * 
   * @return bool
   */
  private function confpwdmatcher() {
    if ($this->pwd!= $this->confPwd) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function for email Validation using the API
   * 
   * @return bool
   */
  private function email_api_error() {
    // Making the GizzleHTTP Client object for the API access 
    $client = new Client();
    $url = "https://api.apilayer.com/email_verification/check?email=$this->email";
    $response = $client->get($url, [
      "headers" => [
        'apikey' => $_ENV['API_KEY'],
        'Accept' => 'application/json',
        'Content-type' => 'application/json'
      ]
    ]);
    // Creating the Array of the API response.
    $obj = json_decode($response->getBody()->getContents() ,TRUE);
    // Email VAlidation using API goes here.
    if ($obj['smtp_check'] == TRUE) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function to validate the song thumbnail formate
   * 
   * @return bool
   */
  public function thumbnailFormate() {
    if(!is_null($this->imageFile)){
      // generate a random name for the file but keep the extension
      $extension = $this->imageFile->getClientOriginalExtension();
      if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension != 'gif') {
        return FALSE;
      }
      else {
        $this->randomPicName = uniqid().".".$this->imageFile->getClientOriginalExtension();
        $path = "../public/cover/";
        $this->imageFile->move($path, $this->randomPicName); // move the file to a path
        return TRUE;
      }
    }
    else {
      return TRUE;
    }
  }

  /**
   * Function to validate the picture formate
   * 
   * @return bool
   */
  public function picFormate() {
    if(!is_null($this->imageFile)){
      // generate a random name for the file but keep the extension
      $extension = $this->imageFile->getClientOriginalExtension();
      if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png') {
        return FALSE;
      }
      else {
        $this->randomPicName = uniqid().".".$this->imageFile->getClientOriginalExtension();
        $path = "../public/profilePic/";
        $this->imageFile->move($path, $this->randomPicName); // move the file to a path
        return TRUE;
      }
    }
    else {
      return TRUE;
    }
  }

  /**
   * FUnction to validate the Auio formate
   * 
   * F@return bool
   */
  private function audioFormate() {
    if(!is_null($this->song)){
      // generate a random name for the file but keep the extension
      $extension = $this->song->getClientOriginalExtension();
      if ($extension != 'mp3' && $extension != 'mpeg') {
        return FALSE;
      }
      else {
        $this->randomSongName = uniqid().".".$this->song->getClientOriginalExtension();
        $path = "../public/songs/";
        $this->song->move($path, $this->randomSongName); // move the file to a path
        return TRUE;
      }
    }
    else {
      return FALSE;
    }
  }
}
