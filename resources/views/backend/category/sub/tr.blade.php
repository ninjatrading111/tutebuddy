@push('after-styles')
<style>
    div.dv {
        width: 30px;
        font-weight: 600;
    }
</style>
@endpush

<?php $space .= '<div class="dv">&nbsp;</div>'; ?>
@foreach($category->children as $category)
<tr data-id="{{ $category->id }}">
    <td class="pr-0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input js-check-selected-row" id="customCheck1_toggle2">
            <label class="custom-control-label" for="customCheck1_toggle2"><span class="text-hide">Check</span></label>
        </div>
    </td>
    <td class="category-name">
        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
            <?php echo $space; ?>
            <div class="avatar avatar-sm mr-8pt">
                @if($category->thumb == '')
                <span class="avatar-title rounded-circle"><?php echo mb_substr($category->name, 0, 2); ?></span>
                @else
                <img src="/storage/uploads/{{$category->thumb}}" alt="Avatar" class="avatar-img rounded-circle">
                @endif
            </div>
            <div class="media-body">
                <div class="d-flex flex-column">
                    <p class="mb-0"><strong class="js-lists-values-category-name">{{ $category->name }}</strong></p>
                    <small class="js-lists-values-category-slug text-50">{{ $category->slug }}</small>
                </div>
            </div>
        </div>
    </td>
    <td class="js-lists-values-status small category-description">
        <?php 
            if (strlen($category->description) > 30)
                $description = mb_substr($category->description, 0, 30) . '...'; 
            else
                $description = $category->description;
        ?>
        {{ $description }}
    </td>
    <!-- <td class="js-lists-values-parent">
        @if ($category->parent()->count() > 0 )
        {{ $category->parent()->first()->name }}
        @else
        No Parent
        @endif
    </td> -->
    <td class="category-action">
        <?php
            $edit_route = route('admin.categories.edit', $category->id);
            $delete_route = route('admin.categories.destroy', $category->id);
        ?>
        @include('backend.buttons.edit', ['edit_route' => $edit_route])
        @include('backend.buttons.delete', ['delete_route' => $delete_route])
    </td>
    <td class="text-right pl-0">
        <a href="javascript:void(0)" class="text-50"><i class="material-icons">more_vert</i></a>
    </td>
</tr>

@include('backend.category.sub.tr', ['category' => $category, 'space' => $space])

@endforeach