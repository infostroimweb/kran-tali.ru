<?php return array('key' => 'group_5e99950d5b69a',
'title' => 'Template - Fields',
'fields' => array(array('key' => 'field_5e99950d68d51',
'label' => __('Column Width', 'guideplugin'),
'name' => 'column_width',
'type' => 'number',
'instructions' => __('Set the column width.', 'guideplugin'),
'required' => '0',
'conditional_logic' => '0',
'wrapper' => array('width' => '',
'class' => '',
'id' => '',
),'default_value' => '100',
'placeholder' => '',
'prepend' => '',
'append' => '%',
'min' => '0',
'max' => '100',
'step' => '1',
),array('key' => 'field_5e99950d68d8b',
'label' => __('Align', 'guideplugin'),
'name' => 'align',
'type' => 'select',
'instructions' => __('Set the text alignment of the content.', 'guideplugin'),
'required' => '0',
'conditional_logic' => '0',
'wrapper' => array('width' => '',
'class' => '',
'id' => '',
),'choices' => array('left' => 'Left',
'center' => 'Center',
'right' => 'Right',
),'default_value' => 'false',
'allow_null' => '0',
'multiple' => '0',
'ui' => '1',
'ajax' => '0',
'return_format' => 'value',
'placeholder' => '',
),array('key' => 'field_5e99950d68dc5',
'label' => __('Margin top', 'guideplugin'),
'name' => 'margin_top',
'type' => 'number',
'instructions' => __('Here you can add some space to the top.', 'guideplugin'),
'required' => '0',
'conditional_logic' => '0',
'wrapper' => array('width' => '',
'class' => '',
'id' => '',
),'default_value' => '0',
'placeholder' => '',
'prepend' => '',
'append' => 'px',
'min' => '0',
'max' => '',
'step' => '1',
),array('key' => 'field_5eb2e276603c6',
'label' => __('Hide on mobile', 'guideplugin'),
'name' => 'hide_on_mobile',
'type' => 'true_false',
'instructions' => __('Check this option if you want to hide this element on mobile devices.', 'guideplugin'),
'required' => '0',
'conditional_logic' => '0',
'wrapper' => array('width' => '',
'class' => '',
'id' => '',
),'message' => '',
'default_value' => '0',
'ui' => '1',
'ui_on_text' => '',
'ui_off_text' => '',
),array('key' => 'field_5ee0342a9c4eb',
'label' => __('Vertical align', 'guideplugin'),
'name' => 'vertical_align',
'type' => 'select',
'instructions' => __('Here you can set the vertical alignment of the element.', 'guideplugin'),
'required' => '0',
'conditional_logic' => '0',
'wrapper' => array('width' => '',
'class' => '',
'id' => '',
),'choices' => array('top' => 'Top',
'center' => 'Center',
'bottom' => 'Bottom',
),'default_value' => 'top',
'allow_null' => '0',
'multiple' => '0',
'ui' => '1',
'ajax' => '0',
'return_format' => 'value',
'placeholder' => '',
),),'location' => array(array(array('param' => 'post_type',
'operator' => '==',
'value' => 'post',
),),),'menu_order' => '0',
'position' => 'normal',
'style' => 'default',
'label_placement' => 'left',
'instruction_placement' => 'label',
'hide_on_screen' => array('0' => 'permalink',
'1' => 'the_content',
'2' => 'excerpt',
'3' => 'discussion',
'4' => 'comments',
'5' => 'revisions',
'6' => 'slug',
'7' => 'author',
'8' => 'format',
'9' => 'page_attributes',
'10' => 'featured_image',
'11' => 'categories',
'12' => 'tags',
'13' => 'send-trackbacks',
),'active' => '0',
'description' => '',
'modified' => '1592571647',
);?>