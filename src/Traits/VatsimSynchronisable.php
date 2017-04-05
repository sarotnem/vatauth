<?php

namespace Theomessin\Vatauth\Traits;

trait VatsimSynchronisable
{
    /**
     * Synchronise a user object from VATSIM with the database
     *
     * @param \stdClass $bundle The user object as sent by the VATSIM server
     * @return User
     */
    public static function sync(\stdClass $bundle)
    {
        $user = self::updateOrCreate([
            'id' => $bundle->id,
            'name' => $bundle->name_first . ' ' . $bundle->name_last,
            'email' => $bundle->email,
        ]);

        //@todo Synchronise pilot and ATC ratings
        return $user;
    }
}