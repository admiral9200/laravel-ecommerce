@include('template.text',['key' => 'title','label' => 'Order Status Title'])
@include('template.select',['key' => 'is_default','label' => 'Is Default','options' => ['0' => 'No','1' => 'Yes']])
@include('template.select',['key' => 'is_last_stage','label' => 'Is Default','options' => ['0' => 'No','1' => 'Yes']])