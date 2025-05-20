@props(['filePath', 'fileName'])
<td class="no-click">
@if($fileName)
    <a href="{{ url($filePath . $fileName) }}" target="_blank">
        <button class="btn btn-primary mb-1 btn-style">View</button>
    </a>
    <a href="{{ url($filePath . $fileName) }}" download>
        <button class="btn btn-info btn-style">Download</button>
    </a>
@endif
</td>