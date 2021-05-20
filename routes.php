<?php


use Nikservik\AdminNotifications\Actions\CreateNotification;
use Nikservik\AdminNotifications\Actions\StoreNotification;
use Nikservik\AdminNotifications\Actions\DeleteNotification;
use Nikservik\AdminNotifications\Actions\EditNotification;
use Nikservik\AdminNotifications\Actions\ListNotifications;
use Nikservik\AdminNotifications\Actions\SearchNotifications;
use Nikservik\AdminNotifications\Actions\UpdateNotification;

ListNotifications::route();
SearchNotifications::route();
CreateNotification::route();
StoreNotification::route();
DeleteNotification::route();
EditNotification::route();
UpdateNotification::route();
