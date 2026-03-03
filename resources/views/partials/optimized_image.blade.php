@php
    /**
     * Partial: optimized_image
     * Params:
     *  - src (string) required
     *  - alt (string) optional
     *  - class (string) optional
     *  - sizes (string) optional
     */
    $src = $src ?? '';
    $alt = $alt ?? '';
    $class = $class ?? '';
    $sizes = $sizes ?? '100vw';
    $widths = [300, 600, 900, 1200];
    $isCloudinary = false;
    $srcset = '';

    if (filter_var($src, FILTER_VALIDATE_URL) && str_contains($src, 'res.cloudinary.com')) {
        $isCloudinary = true;
        $pos = strpos($src, '/image/upload/');
        if ($pos !== false) {
            $base = substr($src, 0, $pos + strlen('/image/upload/'));
            $rest = substr($src, $pos + strlen('/image/upload/'));
            foreach ($widths as $w) {
                $url = $base . "w_{$w},f_auto,q_auto/" . $rest;
                $srcset .= $url . ' ' . $w . 'w, ';
            }
            $srcset = rtrim($srcset, ', ');
            // Default small src
            $defaultSrc = $base . 'w_300,f_auto,q_auto/' . $rest;
        } else {
            $defaultSrc = $src;
        }
    } else {
        $defaultSrc = $src ?: 'https://via.placeholder.com/400x300?text=No+Image';
    }
@endphp

<img
    src="{{ $defaultSrc }}"
    @if(!empty($srcset)) srcset="{{ $srcset }}" @endif
    sizes="{{ $sizes }}"
    class="{{ $class }}"
    alt="{{ $alt }}"
    loading="lazy"
    decoding="async"
>
