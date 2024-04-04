var el = wp.element.createElement;
    var __ = wp.i18n.__;
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPostStatusInfo = wp.editPost.PluginPostStatusInfo;
    var buttonControl = wp.components.Button;

    function macheteGutenButton({}) {
        return el(
            PluginPostStatusInfo,
            {
                className: 'machete-duplicate-post-status-info'
            },
            el(
                buttonControl,
                {
                    isTertiary: true,
                    name: 'machete_page_link_guten',
                    isLink: true,
                    title: machete_params.machete_post_title,
                    href : machete_params.machete_duplicate_nonce_url
                }, machete_params.machete_post_text
            )
        );
    }

    registerPlugin( 'machete-duplicate-post-status-info-plugin', {
        render: macheteGutenButton
    } );