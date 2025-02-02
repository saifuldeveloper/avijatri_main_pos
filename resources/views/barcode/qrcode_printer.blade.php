<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        @import url(//fonts.googleapis.com/earlyaccess/notosansbengali.css);
        @import url(//fonts.googleapis.com/css?family=Oxygen+Mono);

        * {
            margin: 0;
            border: 0;
            padding: 0;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .bar-right {
            position: absolute;
            right: 5px;
        }

        .shoebarsize {
            margin-top: -10px;
            margin-left: 22px;
        }



        .barcode {
            width: 3in;
            font-size: 11px;
            overflow: hidden;
        }

        .barcode-col {
            margin: 5x;
            width: 1.35in;
            height: 0.8in;
            position: relative;
            margin-top: 7px;
            margin-left: 3px;
            margin-right: 4px;

        }

        .barcode-left {
            float: left;
            /* border: 1px solid red;
            border-radius: 5px; */

        }

        .barcode-right {
            float: right;
            /* border: 1px solid red;
            border-radius: 5px; */
        }

        .barcode-col canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 1.35in;
            height: 0.65in;
        }

        .barcode-col p {
            position: absolute;
        }

        .company-name {
            top: 0;
            left: 0.08in;
            font-weight: bold;
        }

        /* .shoe-id {
   top: 0;
   right: 0.08in;
   font-weight: bold;
   font-family: 'Oxygen Mono', monospace;
  } */

        .shoe-description {
            bottom: 24px;
            text-align: left;
            padding-left: 25px;
        }

        .print-button {
            background: green;
            text-align: center;
            text: white;
            color: white;
            padding: 13px 42px 15px;
            margin-left: 98px;
            top: 63px;
            margin-top: 12px;
            border-radius: 6px;
            font-size: -12px !important;

        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>

</head>

<body>
    <button class="print-button">Print</button>
    @foreach ($entries as $entry)
        <div class="barcode">

            @for ($i = 0; $i < $entry->count; $i++)
                <div class="barcode-col {{ $i % 2 == 0 ? 'barcode-left' : 'barcode-right' }}">
                    <div class="bar-first" style="padding-top: 30px !important;margin: 0px;margin: -12px;">
                        <p class="shoebarsize"><b>নাম:</b>
                            {{ $entry->shoe->category->full_name }}-{{ $entry->shoe->color->name }}</p>
                    </div>
                    <div class="bar-left" style="margin-right: 10px; margin-top: 6px;">
                        <p class="company-name" style="rotate: 270deg; margin-left: -14px; margin-top: 40px;">অভিযাত্রী
                        </p>
                        <p class="shoe-description">
                            {{-- <b>সাইজ:</b> L <br> --}}
                            <b>দাম:</b>
                            {{ englishToBanglaNumber(number_format($entry->shoe->retail_price, 2, '.', '')) }}<br>
                            <b class="shoe-id">{{ $entry->shoe->id }}</b>
                        </p>
                    </div>
                    <div class="bar-right">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($entry->shoe->id, 'QRCODE') }}"
                            alt="barcode" style="height:35px;padding-top:15px;">
                    </div>
                </div>
            @endfor
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jsbarcode/3.5.8/barcodes/JsBarcode.code128.min.js">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.barcode-col canvas').each(function() {
                var shoeid = $(this).attr('data-id');
                var id = $(this).attr('id');
                JsBarcode('#' + id, shoeid, {
                    format: 'CODE128',
                    height: 12,
                    width: 2,
                    displayValue: false
                });
            });
            document.querySelector(".print-button").addEventListener("click", function() {
                // Function to execute when button is clicked
                window.print();
            });
        });
    </script>

</body>

</html>
