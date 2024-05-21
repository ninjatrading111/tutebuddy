<?php $space .= '-'; ?>
@foreach($category->children as $category)
    <option value="{{ $category->id }}"
        <?php if($selected == $category->id) echo 'selected'; ?>>
    {{ $space }} {{ $category->name }}
    </option>
    @include('backend.category.sub.option', ['category' => $category, 'space' => $space, 'selected' => $selected])
@endforeach