<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use HTMLPurifier;
use HTMLPurifier_Config;

class UpdateProgramContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        if (is_array($validated)) {
            if (isset($validated['body']) && !empty($validated['body'])) {
                $config = HTMLPurifier_Config::createDefault();
                
                // Konfigurasi Whitelist HTML Purifier yang ketat namun ramah WYSIWYG
                $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title],ul,ol,li,br,span[class|style],strong,em,sub,sup,h1,h2,h3,h4,h5,h6,div[class|style]');
                $config->set('CSS.AllowedProperties', 'color,background-color,font-weight,text-align,text-decoration,font-style');
                $config->set('HTML.SafeIframe', false);
                
                $purifier = new HTMLPurifier($config);
                $validated['body'] = $purifier->purify($validated['body']);
            }

            // Juga sanitasi input icon jika ada kode HTML/SVG yang berbahaya
            if (isset($validated['icon']) && !empty($validated['icon'])) {
                // Jika icon hanyalah sebuah teks angka/biasa (misal "1"), lewati Purifier agar tidak dibungkus atau dibersihkan.
                if (preg_match('/<[a-z][\s\S]*>/i', $validated['icon'])) {
                    $config = HTMLPurifier_Config::createDefault();
                    // Izinkan tag svg, path, polyline, line, dll.
                    $config->set('HTML.Allowed', 'svg[width|height|viewBox|fill|stroke|stroke-width|stroke-linecap|stroke-linejoin|class],path[d|fill|stroke|stroke-width],polyline[points],line[x1|y1|x2|y2|stroke|stroke-width],circle[cx|cy|r|fill|stroke|stroke-width],rect[width|height|x|y|fill|stroke],g[fill|stroke]');
                    
                    $purifier = new HTMLPurifier($config);
                    $validated['icon'] = $purifier->purify($validated['icon']);
                }
            }
        }

        return data_get($validated, $key, $default);
    }
}
