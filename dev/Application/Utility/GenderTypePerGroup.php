<?php


namespace Dev\Application\Utility;

class GenderTypePerGroup
{
    /**
     *
     */
    const MALE_STUDENTS = 'male_students';

    /**
     *
     */
    const FEMALE_STUDENTS = 'female_students';

    /**
     *
     */
    const BOTH = 'both';

    public static function methods(): array
    {
        return [
            self::MALE_STUDENTS,
            self::FEMALE_STUDENTS,
            self::BOTH
        ];
    }
}
