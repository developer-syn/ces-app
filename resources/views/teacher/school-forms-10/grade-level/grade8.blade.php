<!-- Grades Table -->
<div class="overflow-x-auto">
    <!-- School Information Section -->
    <div class="p-2  border border-gray-800">
        <div class="grid grid-cols-1 gap-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-12">School:</span>
                <span
                    class="border-b border-gray-800 flex-1">&nbsp;</span>
                <span class="font-semibold w-24 ml-4">School ID:</span>
                <span
                    class="border-b border-gray-800 w-32">&nbsp;</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-semibold w-12">District:</span>
                <span
                    class="border-b border-gray-800">&nbsp;</span>
                <span class="font-semibold ml-1">Division:</span>
                <span
                    class="border-b border-gray-800 flex-1 w-48">&nbsp;</span>
                <span class="font-semibold ml-4">Region:</span>
                <span
                    class="border-b border-gray-800">&nbsp;</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-semibold">Classified as Grade:</span>
                <span
                    class="border-b border-gray-800 w-16 text-center">&nbsp;</span>
                <span class="font-semibold ml-4">Section:</span>
                <span
                    class="border-b border-gray-800 w-40">&nbsp;</span>
                <span class="font-semibold ml-4">School Year:</span>
                <span
                    class="border-b border-gray-800">&nbsp;</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-semibold">Name of Adviser/Teacher:</span>
                <span
                    class="border-b border-gray-800 flex-1">&nbsp;</span>
                <span class="font-semibold ml-4">Signature:</span>
                <span class="border-b border-gray-800 w-48">&nbsp;</span>
            </div>
        </div>
    </div>
    <!-- Grades Table -->
    <div class="border-1 border-gray-800 overflow-hidden">
        <table class="w-full">
            <thead class="">
                <tr>
                    <th class="border border-gray-800 p-2 text-left" rowspan="2">
                        Learning
                        Areas</th>
                    <th class="border border-gray-800 p-2 text-center" colspan="4">
                        Quarter</th>
                    <th class="border border-gray-800 p-2 text-center" rowspan="2">
                        Final
                        Rating</th>
                    <th class="border border-gray-800 p-2 text-center" rowspan="2">
                        Remarks</th>
                </tr>
                <tr>
                    <th class="border border-gray-800 p-2 text-center">1</th>
                    <th class="border border-gray-800 p-2 text-center">2</th>
                    <th class="border border-gray-800 p-2 text-center">3</th>
                    <th class="border border-gray-800 p-2 text-center">4</th>
                </tr>
            </thead>
            <tbody>
                @foreach (['Mother Tongue', 'Filipino', 'English', 'Mathematics', 'Science', 'Araling Panlipunan', 'EEP / TLE', 'MAPEH', 'Music', 'Arts', 'Physical Education', 'Health', 'Eduk. sa Pagpapakatao', '*Arabic Language', '*Islamic Values Education'] as $subject)
                    <tr>
                        <td class="border border-gray-800 p-2">{{ $subject }}</td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                        <td class="border border-gray-800 p-2 text-center"></td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td class="border border-gray-800 p-2 text-center">
                        General Average</td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                    <td class="border border-gray-800 p-2 text-center"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Remedial Classes -->
    <div class="mt-4 border-1 border-gray-800 overflow-hidden">
        <div class="p-2 border-b-2 border-gray-800">
            <span class="font-bold">Remedial Classes</span>
            <span class="ml-4">Date Conducted: _____________ to _____________</span>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border border-gray-800 p-2">Learning Areas</th>
                    <th class="border border-gray-800 p-2">Final Rating</th>
                    <th class="border border-gray-800 p-2">Remedial Class Mark</th>
                    <th class="border border-gray-800 p-2">Recomputed Final Grade</th>
                    <th class="border border-gray-800 p-2">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td class="border border-gray-800 p-2">&nbsp;</td>
                        <td class="border border-gray-800 p-2">&nbsp;</td>
                        <td class="border border-gray-800 p-2">&nbsp;</td>
                        <td class="border border-gray-800 p-2">&nbsp;</td>
                        <td class="border border-gray-800 p-2">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
