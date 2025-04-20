<table>
    <tbody>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            @if (!empty($column))
                @for ($i = 0; $i < $column - 4; $i++)
                    <td></td>
                @endfor
            @endif
            <td colspan="4" style="border-top: 1px solid #000; text-align: center; font-size: 8px; vertical-align: top;">
                <i>(Signature over Printed Name)</i>
            </td>
        </tr>
    </tbody>
</table>