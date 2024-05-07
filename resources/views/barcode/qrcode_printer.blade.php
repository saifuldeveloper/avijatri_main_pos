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

        .barcode {
            width: 2.1in;
            font-size: 11px;
            overflow: hidden;
        }

        .barcode-col {
            margin: 0.1in 0;
            position: relative;
        }

        .barcode-left {
            display: flex;
            margin: 5px;
            padding: 5px;
            border: 1px solid red;
            border-radius: 6px;
        }

        .barcode-right {
            float: right;
        }

        /* .barcode-col canvas {
   position: absolute;
   top: 0;
   left: 0;
   width: 1.35in;
   height: 0.65in;
  } */



        .company-name {
            top: 0;
            left: 0.08in;
            font-weight: bold;
        }

        .shoe-id {
            top: 0;
            right: 0.08in;
            font-weight: bold;
            font-family: 'Oxygen Mono', monospace;
        }

        .shoe-description {
            bottom: 0;
            right: 0.08in;
            text-align: left;
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

        .bar-right {
            position: absolute;
            right: 5px;
        }
    </style>

</head>

<body>
    <button class="print-button">Print</button>
	@foreach ($entries as $entry)
    @for ($i = 0; $i < $entry->count; $i++)
        <div class="barcode">
            <div class="barcode-col {{ $i % 1 == 0 ? 'barcode-left' : 'barcode-right' }}">
                <div class="bar-first" style="padding-top: 41px !important;margin: 0px;margin: -12px;">
                    <p class="company-name" style="rotate: 270deg;">অভিযাত্রী</p>
                </div>
                <div class="bar-left" style="margin-right: 10px; margin-top: 6px;">
                    <p class="shoebarsize"><b>Size:</b> 42</p>
                    <p class="shoe-description">
                        <b>Name:</b> {{ $entry->shoe->category->full_name }}-{{ $entry->shoe->color->name }}<br>
                        <b>দাম:</b> {{ number_format($entry->shoe->retail_price, 2, '.', '') }}
                    </p>
                    <p class="shoe-id">{{ $entry->shoe->id }}</p>
                </div>
                <div class="bar-right">
                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($entry->shoe->id, 'QRCODE') }}" alt="barcode" style="height:35px;padding-top:13px;">
                </div>
            </div>
        </div>
    @endfor
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
