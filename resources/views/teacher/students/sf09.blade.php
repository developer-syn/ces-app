<x-app-layout>

    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container to hold both columns side by side */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
        }

        /* Left column */
        .column-left {
            flex: 1 1 45%;
            /* Takes about half the container width */
            min-width: 280px;
            border-right: 1px solid #ccc;
            padding-right: 20px;
        }

        /* Right column */
        .column-right {
            flex: 1 1 45%;
            min-width: 280px;
            padding-left: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table thead tr {
            background: #e0e0e0;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        /* Headings and spacing */
        h2,
        h3,
        h4 {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        /* Smaller screen adjustments */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 10px;
            }

            .column-left,
            .column-right {
                border: none;
                padding: 0;
            }
        }
    </style>
    <div class="py-4">
        {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="container">
                    <!-- Left Column -->
                    <section class="column-left">
                        <h2>Attendance Record</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Jun</th>
                                    <th>Jul</th>
                                    <th>Aug</th>
                                    <th>Sep</th>
                                    <th>Oct</th>
                                    <th>Nov</th>
                                    <th>Dec</th>
                                    <th>Jan</th>
                                    <th>Feb</th>
                                    <th>Mar</th>
                                    <th>Apr</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>No. of School Days</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Days Present</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Days Absent</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>Parent/Guardian's Signature</h3>
                        <p>1st Quarter: __________________________</p>
                        <p>2nd Quarter: __________________________</p>
                        <p>3rd Quarter: __________________________</p>
                        <p>4th Quarter: __________________________</p>
                    </section>

                    <!-- Right Column -->
                    <section class="column-right">
                        <h2>Republic of the Philippines<br>
                            Department of Education</h2>
                        <p><strong>Region</strong>: ________ | <strong>Division</strong>: ________ |
                            <strong>District</strong>: ________ | <strong>School</strong>: ________
                        </p>
                        <h3>LEARNER'S PROGRESS REPORT CARD</h3>
                        <p><strong>School Year:</strong> 20XX - 20XX</p>
                        <p><strong>Name:</strong> __________________________</p>
                        <p><strong>Age:</strong> ______</p>
                        <p><strong>Grade:</strong> ______</p>
                        <p><strong>Section:</strong> ______</p>
                        <p><strong>LRN:</strong> __________________________</p>

                        <p><em>Dear Parents,</em><br>
                            This report card shows the ability and the progress your child has made in different
                            learning areas.
                            Kindly examine and see how your child is performing.</p>

                        <p><strong>Teacher:</strong> __________________________</p>
                        <p><strong>Head Teacher/Principal:</strong> __________________________</p>

                        <h4>Certificate of Transfer</h4>
                        <p><strong>Grade &amp; Section:</strong> ________</p>
                        <p><strong>Eligible for Admission to Grade:</strong> ________</p>
                        <p><strong>School:</strong> __________________________</p>
                        <p><strong>Date:</strong> ________</p>
                        <p><strong>Principal:</strong> __________________________</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
