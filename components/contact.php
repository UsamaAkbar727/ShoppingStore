<?php
include("../mailer.php");
include("../configshoppingstore.php");
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "support@yourshop.com";
    $subject = "Contact Form Submission from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    if (sendEmail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Failed to send message. Please try again.'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_description; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4f46e5",
                        secondary: "#10b981",
                        accent: "#f9fafb",
                        dark: "#1f2937"
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.5s ease-out'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
        }
        
        .contact-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
            overflow: hidden;
        }
        
        .contact-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(79,70,229,0.05) 0%, rgba(79,70,229,0) 70%);
            z-index: 0;
        }
        
        .contact-card {
            background: white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .contact-icon {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4f46e5;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .contact-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.12);
        }
        
        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        
        .form-input:focus {
            border-color: #a5b4fc;
            box-shadow: 0 0 0 3px rgba(165, 180, 252, 0.3);
            background-color: white;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -2px rgba(79, 70, 229, 0.1);
        }
        
        .animate-delay-100 {
            animation-delay: 0.1s;
        }
        .animate-delay-200 {
            animation-delay: 0.2s;
        }
    </style>
</head>

<body class="text-gray-800">

<section class="contact-section py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto relative">
        <!-- Decorative elements -->
        <div class="absolute -left-20 -top-20 w-64 h-64 rounded-full bg-purple-100 opacity-20 mix-blend-multiply filter blur-xl"></div>
        <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-indigo-100 opacity-20 mix-blend-multiply filter blur-xl"></div>
        
        <div class="text-center mb-16 animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-purple-600">Contact Us</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Have questions or need assistance? Our team is ready to help you with anything you need.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-8">
                <div class="contact-card p-10 animate-fade-in animate-delay-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white mr-3">1</span>
                        Contact Information
                    </h2>
                    
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 contact-icon p-4 rounded-xl mr-6 float">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Our Location</h3>
                                <p class="mt-2 text-gray-600">123 Shopping Street, Lahore, Pakistan</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 contact-icon p-4 rounded-xl mr-6 float" style="animation-delay: 0.3s">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Phone Number</h3>
                                <p class="mt-2 text-gray-600">+92 300 1234567</p>
                                <p class="mt-1 text-gray-600">Mon-Fri: 9am-6pm</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 contact-icon p-4 rounded-xl mr-6 float" style="animation-delay: 0.6s">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Email Address</h3>
                                <p class="mt-2 text-gray-600">support@yourshop.com</p>
                                <p class="mt-1 text-gray-600">Response within 24 hours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-card p-10 animate-fade-in animate-delay-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-white mr-3">2</span>
                        Visit Our Store
                    </h2>
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden">
                        <div class="bg-gray-200 w-full h-64 rounded-xl flex items-center justify-center text-gray-400">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3403.828535472855!2d73.0941809743002!3d31.44638705079584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3922696ac9459bed%3A0x4d837d0ef403530e!2sCareer%20Institute%20-%20Millat%20Chowk%20Branch%20Faisalabad!5e0!3m2!1sen!2s!4v1755324965190!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="https://www.google.com/maps/place/Career+Institute+-+Millat+Chowk+Branch+Faisalabad/@31.4463871,73.094181,17z/data=!3m1!4b1!4m6!3m5!1s0x3922696ac9459bed:0x4d837d0ef403530e!8m2!3d31.4463825!4d73.0967559!16s%2Fg%2F11qfzf43_b?entry=ttu&g_ep=EgoyMDI1MDgxMy4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="w-full py-3 px-6 border border-primary text-primary rounded-lg font-medium hover:bg-primary hover:text-white transition duration-300">
                            Get Directions
                        </a>
                    </div>
                </div>
            </div>

            <div class="contact-card p-10 animate-fade-in">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white mr-3">3</span>
                    Send Us a Message
                </h2>
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="name" name="name" required
                                class="form-input pl-10 w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="form-input pl-10 w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3">
                                <i class="fas fa-comment-alt text-gray-400"></i>
                            </div>
                            <textarea id="message" name="message" rows="5" required
                                class="form-input pl-10 w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"></textarea>
                        </div>
                    </div>

                    <button type="submit"
                        class="submit-btn w-full text-white py-3 px-6 rounded-lg font-medium transition duration-300 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>
</body>
</html>