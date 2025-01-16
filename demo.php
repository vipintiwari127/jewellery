<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Popup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
       
        .col-lg-12 {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }

        .single-new-product {
            position: relative;
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product-img img {
            width: 100%;
            border-radius: 5px;
        }

        .product-content h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .product-price-star i {
            color: #ffcc00;
        }

        .price {
            margin: 10px 0;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Popup styles */
        .popup-container {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            width: 300px;
            z-index: 10;
        }

        .popup-content {
            position: relative;
        }

        .close-popup {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }

        .contact-icons {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
        }

        .contact-icons a {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 20px; /* Increased font size */
            color: #333;
            opacity: 0; /* Initially hidden */
            animation: slideIn 0.5s forwards; /* Animation */
        }

        .contact-icons a:nth-child(1) {
            animation-delay: 0.2s; /* Delay for WhatsApp icon */
        }

        .contact-icons a:nth-child(2) {
            animation-delay: 0.4s; /* Delay for Call icon */
        }

        .contact-icons a:hover {
            color: #007bff;
        }

        .left-icon i {
            color: #25D366;
            margin-right: 5px;
            animation: bounce 1s infinite; /* Bounce animation */
        }

        .right-icon i {
            color: #007bff;
            margin-right: 5px;
            animation: bounce 1s infinite; /* Bounce animation */
        }

        /* Animation for sliding in the icons */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bounce animation */
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0);
            }
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9;
        }
    </style>
</head>
<body>
    <div class="col-lg-12">
        <div class="single-new-product">
            <div class="product-img">
                <a href="#">
                    <img src="https://via.placeholder.com/300" class="first_img" alt="Product Image" />
                </a>
            </div>
            <div class="product-content text-center">
                <a href="#">
                    <h3>Beaumont Summit</h3>
                </a>
                <div class="product-price-star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div>
                <div class="price">
                    <h4>Rs33.00</h4>
                    <h3 class="del-price"><del>Rs45.00</del></h3>
                </div>
                <button class="btn btn-primary inquire-now-btn" onclick="showPopup()">Inquire Now</button>
            </div>

            <!-- Popup -->
            <div class="popup-container" id="popup">
                <div class="popup-content">
                    <span class="close-popup" onclick="hidePopup()">&times;</span>
                    <div class="contact-icons">
                        <a href="https://wa.me/your-number" target="_blank" class="left-icon">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="tel:+1234567890" class="right-icon">
                            <i class="fas fa-phone-alt"></i> Call
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="hidePopup()"></div>

    <script>
        function showPopup() {
            document.getElementById("popup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
    </script>
</body>
</html>
