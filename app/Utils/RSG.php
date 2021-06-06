<?php
namespace WP_SENPAI\Utils;

if ( !defined( 'WPINC' ) ) {die();}
    /**
	 * Random String Generator helper class it's used to speedup fetch random strings and integers.
	 * @category Class
	 * @author amine safsafi
	 */
class RSG {
	/**
	 * @ignore
	 */
    protected $alphabet;

	/**
	 * @ignore
	 */
    protected $alphabetLength;

	/**
	 * $generator = new \WP_SENPAI\Utils\RSG();
	 * @param string $alphabet
	 * @author amine safsafi
	 * @return void
	 */
    public function __construct($alphabet = '')
    {
        if ('' !== $alphabet) {
            $this->setAlphabet($alphabet);
        } else {
            $this->setAlphabet(
                  implode(range('a', 'z'))
                . implode(range('A', 'Z'))
                . implode(range(0, 9))
            );
        }
    }

	/**
     * Bsic usage example of setAlphabet
     * 
     * ```
     * $customAlphabet = '0123456789abcdefjhigklmnopqrstuvwxyz';
     * $generator = new \WP_SENPAI\Utils\RSG($customAlphabet);
     * $generator->setAlphabet($customAlphabet);
     * ```
     * 
     * *test 1
     * *test 2
     * 
	 * @param string $alphabet
	 * @author amine safsafi
	 * @return void
	 */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }


    /**
     * $s = $generator->generate(15);
	 * @author amine safsafi
     * @param int $length
     * @return string
	 */
    public function generate($length)
    {
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->getRandomInteger(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

    /**
     * $i = $generator->getRandomInteger(1,100);
     * @param int $min
     * @param int $max
     * @return int
     * @author amine safsafi
     */
    protected function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;

        } while ($rnd >= $range);

        return ($min + $rnd);
    }

}