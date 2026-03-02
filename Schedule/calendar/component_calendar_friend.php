    <style>
        .calendar-high {
            padding-top: 150px;
        }
        .font-thai{
            font-family: 'Kanit', sans-serif;
        }
        .red-text {
        color: #e62d2d; /* สีแดง */
        }
        .blue-text {
        color: #2c80b5; /* สีน้ำเงิน */
        }
    </style> 
    <div class="container" id="contentBox">
        <div class="calendar-high font-thai">
            <div class="container" id="contentBox">
                <h3 class="text-center mt-4">ปฏิทินกำหนดการของคุณกับเพื่อน</h3>
                <div class="text-center">
                    <i class="fas fa-circle" style="color: #e62d2d;"></i> <span class="red-text">สีแดงคือกำหนดการเร่งด่วน</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-circle" style="color: #2c80b5;"></i> <span class="blue-text">สีน้ำเงินคือกำหนดการทั่วไป</span>
                </div>
                <hr>
                <div class="container py-3" id='calendar'></div>
            </div>
            <div class="container py-3" id='calendar'></div>
        </div>
    </div>
</div>