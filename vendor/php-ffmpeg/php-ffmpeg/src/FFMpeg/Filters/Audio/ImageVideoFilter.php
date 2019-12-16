<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) PHP-FFMpeg and Contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

/**
 * Converts the audio to video and `artwork` as video content.
 *
 * @author     pralhad.shrestha05@gmail.com
 * @copyright  PHP-FFMpeg and Contributors
 * @license    MIT License
 * @package    FFMpeg\Filters
 * @subpackage Audio
 */
class ImageVideoFilter implements AudioFilterInterface
{
    use TPriorityFilter;

    /**
     * Presets of ffmpeg quality levels
     */
    protected const FFMPEG_PRESETS = [
        'ultrafast', 'superfast', 'veryfast', 'faster', 'fast', 'medium', 'slow', 'slower', 'veryslow'
    ];

    /**
     * @var string
     */
    private $artwork;
    /**
     * @var string
     */
    private $preset;
    /**
     * @var int
     */
    private $priority;

    public function __construct(string $artwork, string $preset, int $priority = 10)
    {
        $this->artwork = $artwork;
        $this->priority = $priority;

        if (!in_array($preset, self::FFMPEG_PRESETS)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Undefined preset (%s) given. Please pass a preset type (one of "%s") to this method.',
                    $this->preset,
                    implode('", "', self::FFMPEG_PRESETS)
                )
            );
        }
        $this->preset = $preset;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Audio $audio, AudioInterface $format) : array
    {
        $commands = ['-loop', '1', '-i', $this->artwork];

        $commands[] = '-preset';
        $commands[] = $this->preset;

        $commands[] = '-shortest';

        return $commands;
    }
}
