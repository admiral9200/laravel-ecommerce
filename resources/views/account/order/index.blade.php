@extends('layouts.user')

@section('content')
<a-row type="flex" class="mb-1" justify="start">
    <a-col>
        <h1>{{  __('Orders') }}</h1>
    </a-col>
</a-row>
<a-row type="flex" :gutter="15" class="mt-1">
    <a-col :span="24">
        <user-order-table inline-template>
            <a-table :columns="columns" row-key="id" :data-source="{{ $userOrders }}">
                <span slot="action" slot-scope="text, record">
                    <a :href="getShowUrl(record)">
                        <a-icon type="eye"></a-icon>
                    </a>
                </span>
            </a-table>
        </user-order-table>
    </a-col>
</a-row>

@endsection
