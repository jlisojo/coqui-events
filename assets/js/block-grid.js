/**
 * Gutenberg Block: Event Grid
 * Renders an interactive event grid block in the WordPress editor.
 */
(function (blocks, element, blockEditor, components, serverSideRender, i18n) {
    var __ = i18n.__;
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var RangeControl = components.RangeControl;
    var ToggleControl = components.ToggleControl;
    var ServerSideRender = serverSideRender;

    registerBlockType('coqui-events/event-grid', {
        title: __('Event Grid', 'coqui-events'),
        description: __('Display upcoming events in a responsive grid layout.', 'coqui-events'),
        icon: 'calendar-alt',
        category: 'widgets',
        keywords: [__('events', 'coqui-events'), __('calendar', 'coqui-events'), __('grid', 'coqui-events')],
        attributes: {
            count: {
                type: 'number',
                default: 3
            },
            title: {
                type: 'string',
                default: ''
            },
            show_button: {
                type: 'boolean',
                default: true
            },
            button_text: {
                type: 'string',
                default: __('View All Events', 'coqui-events')
            },
            button_url: {
                type: 'string',
                default: ''
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, { key: 'inspector' },
                    el(PanelBody, { title: __('Event Grid Settings', 'coqui-events'), initialOpen: true },
                        el(TextControl, {
                            label: __('Grid Title', 'coqui-events'),
                            value: attributes.title,
                            onChange: function (value) {
                                setAttributes({ title: value });
                            }
                        }),
                        el(RangeControl, {
                            label: __('Number of Events', 'coqui-events'),
                            value: attributes.count,
                            onChange: function (value) {
                                setAttributes({ count: value });
                            },
                            min: 1,
                            max: 12
                        }),
                        el(ToggleControl, {
                            label: __('Show "View All" Button', 'coqui-events'),
                            checked: attributes.show_button,
                            onChange: function (value) {
                                setAttributes({ show_button: value });
                            }
                        }),
                        attributes.show_button && el(TextControl, {
                            label: __('Button Text', 'coqui-events'),
                            value: attributes.button_text,
                            onChange: function (value) {
                                setAttributes({ button_text: value });
                            }
                        }),
                        attributes.show_button && el(TextControl, {
                            label: __('Button Custom URL (optional)', 'coqui-events'),
                            value: attributes.button_url,
                            placeholder: __('Leave empty for default archive URL', 'coqui-events'),
                            onChange: function (value) {
                                setAttributes({ button_url: value });
                            }
                        })
                    )
                ),
                el('div', { key: 'render', className: 'simple-events-block-preview' },
                    el(ServerSideRender, {
                        block: 'coqui-events/event-grid',
                        attributes: attributes
                    })
                )
            ];
        },
        save: function () {
            // Rendered dynamically on server side in PHP
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.serverSideRender || window.wp.components.ServerSideRender,
    window.wp.i18n
);
