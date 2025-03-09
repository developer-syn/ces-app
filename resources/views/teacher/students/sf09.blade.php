<x-app-layout>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        /* Container for screen display remains flexible, but we override for printing */
        .container {
            display: flex;
            flex-wrap: wrap;
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
        }

        /* Print-specific adjustments */
        @media print {

            /* Ensure the printed page uses the desired size */
            @page {
                size: 11in 8.5in;
                margin: 0;
            }

            .container {
                width: 11in;
                height: 8.5in;
                margin: 0 auto;
            }
        }

        /* Left column */
        .column-left {
            flex: 1 1 40%;
            min-width: 280px;
            padding-right: 20px;
        }

        /* Right column */
        .column-right {
            flex: 1 1 60%;
            min-width: 280px;
            padding-left: 130px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            height: 30px;
        }

        table td:first-child {
            text-align: left;
            font-weight: normal;
            padding-left: 10px;
        }

        /* Headings and spacing */
        h2 {
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        h3 {
            margin: 15px 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }

        /* Parent signature section */
        .parent-signature {
            margin-top: 30px;
            text-align: center;
        }

        .parent-signature h3 {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .signature-line {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            text-align: left;
        }

        .signature-line span {
            width: 80px;
            font-weight: normal;
            text-align: left;
        }

        .signature-line .line {
            flex: 1;
            padding-top: 10px;

            border-bottom: 1px solid #000;
        }

        /* School header */
        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .school-header img {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .school-header-text {
            text-align: center;
        }

        .school-header-text h2 {
            margin: 0;
            line-height: 1.3;
        }

        /* School details */
        .school-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 10px 0;
        }

        .school-detail {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            width: 48%;
        }

        .school-detail .label {
            margin-right: 5px;
        }

        .school-detail .line {
            flex: 1;
            padding-top: 10px;

            padding-top: 10px;
            border-bottom: 1px solid #000;
        }

        /* Report card header */
        .report-card-header {
            text-align: center;
            margin: 15px 0;
        }

        .report-card-header h3 {
            margin-bottom: 5px;
        }

        /* Student info */
        .student-info {
            margin: 15px 0;
        }

        .student-info-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .student-info-row .label {
            min-width: 50px;
        }

        .student-info-row .line {
            flex: 1;
            padding-top: 10px;

            border-bottom: 1px solid #000;
        }

        .student-info-row .short-label {
            min-width: 40px;
            margin-left: 15px;
        }

        .student-info-row .short-line {
            width: 120px;
            border-bottom: 1px solid #000;
        }

        /* Message section */
        .parent-message {
            margin: 15px 0;
            line-height: 1.5;
        }

        .parent-message p {
            margin-bottom: 5px;
        }

        /* Signature section */
        .signature-section {
            margin: 20px 0;
        }

        .signature-box {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-bottom: 15px;
        }

        .signature-box .line {
            width: 250px;
            padding-top: 10px;

            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        /* Certificate section */
        .certificate {
            margin-top: 15px;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }

        .certificate h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        .certificate-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .certificate-row .label {
            margin-right: 10px;
        }

        .certificate-row .line {
            flex: 1;
            padding-top: 10px;

            border-bottom: 1px solid #000;
        }

        .certificate-row .label-right {
            margin: 0 10px;
        }

        .certificate-signatures {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .cert-signature {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 45%;
        }

        .cert-signature .line {
            width: 100%;
            padding-top: 10px;

            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .cancellation {
            margin-top: 20px;
        }

        .cancellation h4 {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        /* Smaller screen adjustments */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 10px;
            }

            .column-left,
            .column-right {
                padding: 0;
            }
        }
    </style>
    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="container">
                    <!-- Left Column -->
                    <section class="column-left">
                        <h2>Attendance Record</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Jun</th>
                                    <th>Jul</th>
                                    <th>Aug</th>
                                    <th>Sept</th>
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
                                    <td>No. of Days Present</td>
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
                                    <td>No. of Times Absent</td>
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

                        <div class="parent-signature">
                            <h3>Parent/Guardian's Signature</h3>
                            <div class="signature-line">
                                <span>1<sup>st</sup> Quarter</span>
                                <div class="line"></div>
                            </div>
                            <div class="signature-line">
                                <span>2<sup>nd</sup> Quarter</span>
                                <div class="line"></div>
                            </div>
                            <div class="signature-line">
                                <span>3<sup>rd</sup> Quarter</span>
                                <div class="line"></div>
                            </div>
                            <div class="signature-line">
                                <span>4<sup>th</sup> Quarter</span>
                                <div class="line"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Right Column -->
                    <section class="column-right">
                        <div class="school-header">
                            <img src="/api/placeholder/60/60" alt="DepEd Seal" />
                            <div class="school-header-text">
                                <h2>Republic of the Philippines</h2>
                                <h2>DEPARTMENT OF EDUCATION</h2>
                            </div>
                        </div>

                        <div class="school-details">
                            <div class="school-detail">
                                <span class="label">Region</span>
                                <div class="line"></div>
                            </div>
                            <div class="school-detail">
                                <span class="label">Division</span>
                                <div class="line"></div>
                            </div>
                            <div class="school-detail">
                                <span class="label">District</span>
                                <div class="line"></div>
                            </div>
                            <div class="school-detail">
                                <span class="label">School</span>
                                <div class="line"></div>
                            </div>
                        </div>

                        <div class="report-card-header">
                            <h3>LEARNER'S PROGRESS REPORT CARD</h3>
                            <p>School Year: 2019-2020</p>
                        </div>

                        <div class="student-info">
                            <div class="student-info-row">
                                <span class="label">Name:</span>
                                <div class="line"></div>
                            </div>
                            <div class="student-info-row">
                                <span class="label">Age:</span>
                                <div class="line" style="flex: 0.3;"></div>
                                <span class="short-label">Sex:</span>
                                <div class="line" style="flex: 0.7;"></div>
                            </div>
                            <div class="student-info-row">
                                <span class="label">Grade:</span>
                                <div class="line" style="flex: 0.2;"></div>
                                <span class="short-label">Section:</span>
                                <div class="line" style="flex: 0.3;"></div>
                                <span class="short-label">LRN:</span>
                                <div class="line" style="flex: 0.5;"></div>
                            </div>
                        </div>

                        <div class="parent-message">
                            <p><i>Dear Parent,</i></p>
                            <p style="padding-left: 50px;"><i>This report card shows the ability and the progress your
                            child has made </p>
                            <p>in the different learning areas as well as his/her progress in core
                                values.</i></p>
                            <p style="padding-left: 50px;"><i>The school welcomes you should you desire to know more about your </p>
                                <p>child's progress.</i></p>
                        </div>

                        <div class="signature-section">
                            <div class="signature-box">
                                <div class="line"></div>
                                <p>Teacher</p>
                            </div>
                            <div class="signature-box">
                                <div class="line"></div>
                                <p>Head Teacher/ Principal</p>
                            </div>
                        </div>

                        <div class="certificate">
                            <h3>Certificate of Transfer</h3>
                            <div class="certificate-row">
                                <span class="label">Admitted to Grade</span>
                                <div class="line" style="flex: 0.3;"></div>
                                <span class="label-right">Section</span>
                                <div class="line" style="flex: 0.4;"></div>
                            </div>
                            <div class="certificate-row">
                                <span class="label">Room</span>
                                <div class="line"></div>
                            </div>
                            <div class="certificate-row">
                                <span class="label">Eligible for Admission to Grade</span>
                                <div class="line"></div>
                            </div>
                            <div class="certificate-row">
                                <span class="label">Approved:</span>
                            </div>

                            <div class="certificate-signatures">
                                <div class="cert-signature">
                                    <div class="line"></div>
                                    <p>Head Teacher/ Principal</p>
                                </div>
                                <div class="cert-signature">
                                    <div class="line"></div>
                                    <p>Teacher</p>
                                </div>
                            </div>

                            <div class="cancellation">
                                <h4>Cancellation of Eligibility to Transfer</h4>
                                <div class="certificate-row">
                                    <span class="label">Admitted in</span>
                                    <div class="line"></div>
                                </div>
                                <div class="certificate-row">
                                    <span class="label">Date:</span>
                                    <div class="line"></div>
                                </div>

                                <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                                    <div class="cert-signature" style="width: 40%;">
                                        <div class="line"></div>
                                        <p>Principal</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
