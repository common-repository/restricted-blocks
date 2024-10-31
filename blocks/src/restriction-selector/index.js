/**
 * Implementation inspired by this article:
 *
 * https://awhitepixel.com/blog/add-custom-settings-to-existing-wordpress-gutenberg-blocks/
 */

/**
 *
 * Add the daextreblRestrictionId attribute to all the blocks.
 *
 * @param settings
 * @param name
 * @returns {*}
 */
function addAttribute(settings, name) {
  if (typeof settings.attributes !== 'undefined') {
    settings.attributes = Object.assign(settings.attributes, {
      daextreblRestrictionId: {
        type: 'int',
      },
    });
  }
  return settings;
}

wp.hooks.addFilter(
    'blocks.registerBlockType',
    'daextrebl/add-attribute',
    addAttribute,
);

/**
 * Add the Restriction selector in all the blocks.
 */
const enhanceControls = wp.compose.createHigherOrderComponent((BlockEdit) => {
  return (props) => {

    const {Fragment} = wp.element;
    const {SelectControl, PanelBody, PanelRow} = wp.components;
    const {InspectorControls} = wp.blockEditor;
    const {attributes, setAttributes} = props;
    const {__} = wp.i18n;

    return (
        <Fragment>
          <BlockEdit {...props} />
          <InspectorControls>
            <PanelBody title={__('Restriction', 'restricted-blocks')}
                       initialOpen={true}>
              <PanelRow className="panel-row-restriction">
                <SelectControl
                    label={ __( 'Name', 'restricted-blocks' ) }
                    value={attributes.daextreblRestrictionId}
                    onChange={val => setAttributes(
                        {daextreblRestrictionId: val})}
                    options={window.DAEXTREBL_PARAMETERS.restrictions}
                />
              </PanelRow>
            </PanelBody>
          </InspectorControls>
        </Fragment>
    );

  };
}, 'enhanceControls');

wp.hooks.addFilter(
    'editor.BlockEdit',
    'daextrebl/enhance-controls',
    enhanceControls,
);