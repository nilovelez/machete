var el = wp.element.createElement;
    var __ = wp.i18n.__;
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPostStatusInfo = wp.editPost.PluginPostStatusInfo;
    var buttonControl = wp.components.Button;

    function macheteCloneGutenButton({}) {
        return el(
            PluginPostStatusInfo,
            {
                className: 'machete-clone-guten-group'
            },
            el(
                buttonControl,
                {
                    isTertiary: true,
                    name: 'machete_page_link_guten',
                    isLink: true,
                    title: machete_params.machete_clone_button_title,
                    href : machete_params.machete_clone_nonce_url
                }, machete_params.machete_clone_button_text
            )
        );
    }

    registerPlugin( 'machete-clone-plugin', {
        render: macheteCloneGutenButton
    } );