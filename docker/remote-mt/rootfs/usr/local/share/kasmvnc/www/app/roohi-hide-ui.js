(function ()
{
    'use strict';

    const streamDefaults = {
        video_quality: '10',
        framerate: '24',
        jpeg_video_quality: '6',
        webp_video_quality: '6',
        dynamic_quality_min: '3',
        dynamic_quality_max: '7',
        video_scaling: '1',
        resize: 'remote',
        vncResize: 'remote',
        view_clip: 'true',
        video_out_time: '3',
        video_area: '65',
        video_time: '5',
        treat_lossless: '7',
        enable_webp: 'true'
    };

    const fixedSelectors = [
        '#noVNC_control_bar_anchor',
        '#noVNC_control_bar',
        '#noVNC_control_bar_hint',
        '#noVNC_keyboard_control',
        '#lsbar',
        '#fileButton',
        '#audioButton',
        '#micButton',
        '#mySidenav',
        '#menuTab',
        '.sidenavnew',
        '#dragHandler',
        '#fullscreenTrigger',
        '#closeMenu',
        '#noVNC_connection_stats'
    ];

    function hideElement(el)
    {
        el.style.display = 'none';
        el.style.visibility = 'hidden';
        el.style.pointerEvents = 'none';
        el.setAttribute('aria-hidden', 'true');
    }

    function hideKnownControls()
    {
        fixedSelectors.forEach((selector) =>
        {
            document.querySelectorAll(selector).forEach(hideElement);
        });
    }

    function apply()
    {
        hideKnownControls();
    }

    function applyStreamDefaults()
    {
        const targetWidth = Math.max(1366, window.innerWidth || 1366);
        const targetHeight = Math.max(768, window.innerHeight || 768);
        const proxyMatch = window.location.pathname.match(/^\/vnc\/(62\d{3})(?:\/|$)/);

        Object.entries(streamDefaults).forEach(([key, value]) =>
        {
            window.localStorage.setItem(key, value);
        });

        if (proxyMatch)
        {
            // Kasm defaults to '/websockify', but behind /vnc/{port}/ this must include the path prefix.
            window.localStorage.setItem('path', 'vnc/' + proxyMatch[1] + '/websockify');
        }

        window.localStorage.setItem('max_video_resolution_x', String(targetWidth));
        window.localStorage.setItem('max_video_resolution_y', String(targetHeight));
    }

    function syncUiStreamSettings()
    {
        if (!window.UI || typeof window.UI.forceSetting !== 'function')
        {
            return;
        }

        try
        {
            const targetWidth = Math.max(1366, window.innerWidth || 1366);
            const targetHeight = Math.max(768, window.innerHeight || 768);
            const proxyMatch = window.location.pathname.match(/^\/vnc\/(62\d{3})(?:\/|$)/);

            window.UI.forceSetting('video_quality', 10, false);
            window.UI.forceSetting('max_video_resolution_x', targetWidth, false);
            window.UI.forceSetting('max_video_resolution_y', targetHeight, false);
            window.UI.forceSetting('framerate', 24, false);
            window.UI.forceSetting('jpeg_video_quality', 6, false);
            window.UI.forceSetting('webp_video_quality', 6, false);
            window.UI.forceSetting('dynamic_quality_min', 3, false);
            window.UI.forceSetting('dynamic_quality_max', 7, false);
            window.UI.forceSetting('video_scaling', 1, false);
            window.UI.forceSetting('resize', 'remote', false);
            window.UI.forceSetting('view_clip', true, false);
            window.UI.forceSetting('video_out_time', 3, false);
            window.UI.forceSetting('video_area', 65, false);
            window.UI.forceSetting('video_time', 5, false);
            window.UI.forceSetting('treat_lossless', 7, false);
            window.UI.forceSetting('enable_webp', true, false);

            if (proxyMatch)
            {
                window.UI.forceSetting('path', 'vnc/' + proxyMatch[1] + '/websockify', false);
            }

            if (typeof window.UI.updateQuality === 'function')
            {
                window.UI.updateQuality(24);
            }
        }
        catch (e)
        {
            // keep UI resilient if Kasm internals change
        }
    }

    applyStreamDefaults();

    document.addEventListener('DOMContentLoaded', apply);
    window.addEventListener('load', () =>
    {
        apply();
        syncUiStreamSettings();
    });
    window.addEventListener('resize', apply, { passive: true });
    window.setTimeout(syncUiStreamSettings, 1200);
    window.setTimeout(syncUiStreamSettings, 3000);
    window.setTimeout(apply, 800);
    window.setTimeout(apply, 2500);
})();