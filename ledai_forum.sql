-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 02, 2024 lúc 10:17 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ledai_forum`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `active_users`
--

CREATE TABLE `active_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(255) DEFAULT NULL,
  `activity_content` text DEFAULT NULL,
  `activity_date` datetime DEFAULT NULL,
  `reference_link` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `activity_type`, `activity_content`, `activity_date`, `reference_link`, `created_at`) VALUES
(1, 1, 'comment', 'Đã bình luận trên video vừa đăng', '2024-10-21 12:33:13', '#', '2024-10-21 22:33:13'),
(2, 2, 'status', 'Đăng trạng thái mới: \"Xin chào mọi người, các bạn thế nào?\"', '2024-09-21 22:33:13', '#', '2024-10-21 22:33:13'),
(3, 3, 'share', 'Chia sẻ một video trên dòng thời gian: \"Bạn thật hài hước, Mr. Bean.\"', '2022-10-21 22:33:13', '#', '2024-10-21 22:33:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$D/d3PRf4zL.qkFfRyNx6YebI1POJkB6Rh9m6sWwRgkZ5Aa0FN/cXm', '2024-09-22 16:37:23'),
(2, 'manager', '123456', '2024-09-22 16:37:23'),
(3, 'admin1', '$2y$10$54TpJtACWsaS398k4emgAe3NnxrWX6n9onh1XtcJOckip9SaZdkC2', '2024-09-22 16:46:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `user_id`, `content`, `created_at`) VALUES
(10, 2, 1, 'ok', '2024-09-22 15:36:34'),
(11, 2, 1, 'khum bít', '2024-09-22 15:44:55'),
(12, 2, 1, 'haha', '2024-09-22 15:49:20'),
(13, 2, 1, 'quào', '2024-09-22 15:50:23'),
(14, 2, 1, 'là', '2024-09-22 15:54:59'),
(15, 2, 1, 'hehe', '2024-09-22 16:01:15'),
(16, 2, 1, 'whuttt', '2024-09-22 16:10:14'),
(17, 3, 5, 'ai biết', '2024-09-23 14:38:06'),
(18, 15, 1, '3.2', '2024-09-29 11:24:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `birthdays`
--

CREATE TABLE `birthdays` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `birthday_date` date NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `birthday_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `birthdays`
--

INSERT INTO `birthdays` (`id`, `user_name`, `birthday_date`, `profile_image`, `background_image`, `event_name`, `birthday_message`) VALUES
(1, 'dai', '2024-10-21', 'uploads/profile_picture/kafka.jpg', 'images/resources/dob2.png', 'Valentine\'s Birthday', 'Leave a message with your best wishes on her profile.'),
(2, 'dai2', '2024-10-22', 'uploads/profile_picture/maki.jpg', 'images/resources/dob2.png', 'Happy Birthday', 'dai2 a great birthday!'),
(3, 'ledai123', '2024-10-23', 'uploads/profile_picture/jingliu.jpg', 'images/resources/dob2.png', 'Birthday Bash', 'ledai123 is celebrating! Leave her a fun message!');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Nguyen Van A', 'nguyenvana@example.com', 'Tôi cần hỗ trợ về tài khoản của mình.', '2024-09-09 13:58:45'),
(2, 'Tran Thi B', 'tranthib@example.com', 'Làm ơn cho tôi biết cách thay đổi mật khẩu.', '2024-09-09 13:58:45'),
(5, 'Nguyen Van A', 'nguyenvana@example.com', 'Tôi cần hỗ trợ về tài khoản của mình.', '2024-09-09 13:59:22'),
(6, 'Tran Thi B', 'tranthib@example.com', 'Làm ơn cho tôi biết cách thay đổi mật khẩu.', '2024-09-09 13:59:22'),
(7, 'Nguyen Van A', 'nguyenvana@example.com', 'Tôi cần hỗ trợ về tài khoản của mình.', '2024-09-09 13:59:41'),
(8, 'Tran Thi B', 'tranthib@example.com', 'Làm ơn cho tôi biết cách thay đổi mật khẩu.', '2024-09-09 13:59:41'),
(10, 'Nguyen Van A', 'nguyenvana@example.com', 'Tôi cần hỗ trợ về tài khoản của mình.', '2024-09-09 14:00:07'),
(11, 'Tran Thi B', 'tranthib@example.com', 'Làm ơn cho tôi biết cách thay đổi mật khẩu.', '2024-09-09 14:00:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `education`
--

INSERT INTO `education` (`id`, `user_id`, `degree`, `institution`, `graduation_year`) VALUES
(1, 1, 'Cử nhân Công nghệ thông tin', 'Đại học Bách Khoa', '2014'),
(2, 2, 'Cử nhân Báo chí', 'Đại học Khoa học Xã hội và Nhân văn', '2016');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `created_at`) VALUES
(1, 'Hội thảo lập trình', 'Một hội thảo về lập trình PHP và MySQL.', '2024-10-15', '2024-09-09 13:58:45'),
(2, 'Ngày hội công nghệ', 'Một sự kiện lớn với nhiều công ty công nghệ.', '2024-11-01', '2024-09-09 13:58:45'),
(7, 'Hội thảo lập trình', 'Một hội thảo về lập trình PHP và MySQL.', '2024-10-15', '2024-09-09 14:00:07'),
(8, 'Ngày hội công nghệ', 'Một sự kiện lớn với nhiều công ty công nghệ.', '2024-11-01', '2024-09-09 14:00:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`) VALUES
(1, 'Làm thế nào để đăng ký tài khoản?', 'Bạn có thể đăng ký tài khoản bằng cách nhấp vào nút Đăng ký ở góc trên bên phải trang.', '2024-09-09 13:58:45'),
(2, 'Tôi quên mật khẩu, phải làm gì?', 'Hãy sử dụng liên kết quên mật khẩu trên trang đăng nhập để đặt lại mật khẩu của bạn.', '2024-09-09 13:58:45'),
(5, 'Làm thế nào để đăng ký tài khoản?', 'Bạn có thể đăng ký tài khoản bằng cách nhấp vào nút Đăng ký ở góc trên bên phải trang.', '2024-09-09 14:00:07'),
(6, 'Tôi quên mật khẩu, phải làm gì?', 'Hãy sử dụng liên kết quên mật khẩu trên trang đăng nhập để đặt lại mật khẩu của bạn.', '2024-09-09 14:00:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorite_movies`
--

CREATE TABLE `favorite_movies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_name` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `movie_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `favorite_movies`
--

INSERT INTO `favorite_movies` (`id`, `user_id`, `movie_name`, `year`, `image_url`, `movie_link`) VALUES
(3, 1, 'Thor Hollywood Movie', 2017, 'images/resources/thor.jpeg', 'https://www.imdb.com/title/tt0800369/');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `follower_id` int(11) NOT NULL,
  `status` enum('pending','accepted') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `question_id`, `created_at`, `follower_id`, `status`) VALUES
(3, 1, 2, '2024-09-17 06:07:16', 0, 'pending'),
(4, 1, 1, '2024-09-17 07:48:47', 0, 'pending'),
(5, 1, 3, '2024-09-17 07:51:01', 0, 'pending'),
(6, 2, 2, '2024-09-19 03:12:52', 0, 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `footer_links`
--

CREATE TABLE `footer_links` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `footer_links`
--

INSERT INTO `footer_links` (`id`, `section_id`, `title`, `url`, `icon_class`, `position`, `created_at`) VALUES
(25, 4, 'facebook', 'https://www.facebook.com/your.ledai98/', 'fa-facebook-square', 1, '2024-10-15 03:48:12'),
(26, 4, 'twitter', 'https://x.com/RelliZUdeen98', 'fa-twitter-square', 2, '2024-10-15 03:48:12'),
(27, 4, 'instagram', 'https://www.instagram.com/cinderellia_03/', 'fa-instagram', 3, '2024-10-15 03:48:12'),
(28, 4, 'Google+', 'https://plus.google.com/discover', 'fa-google-plus-square', 4, '2024-10-15 03:48:12'),
(29, 4, 'Pintrest', 'https://www.pinterest.com/', 'fa-pinterest-square', 5, '2024-10-15 03:48:12'),
(30, 5, 'Về chúng tôi', 'about.php', '', 1, '2024-10-15 03:48:59'),
(31, 5, 'Liên hệ', 'contact.php', '', 2, '2024-10-15 03:48:59'),
(32, 5, 'Điều khoản-Dịch vụ', 'privacy.php', '', 3, '2024-10-15 03:48:59'),
(33, 5, 'RSS syndication', '#', '', 4, '2024-10-15 03:48:59'),
(34, 5, 'Sitemap', 'sitemap.php', '', 5, '2024-10-15 03:48:59'),
(35, 6, 'Cho thuê', '#', '', 1, '2024-10-15 03:49:17'),
(36, 6, 'Gửi lộ trình', '#', '', 2, '2024-10-15 03:49:17'),
(37, 6, 'Cách thức hoạt động', '#', '', 3, '2024-10-15 03:49:17'),
(38, 6, 'Danh sách đại lý', '#', '', 4, '2024-10-15 03:49:17'),
(39, 6, 'Xem tất cả', '#', '', 5, '2024-10-15 03:49:17'),
(40, 7, 'Câu hỏi thường gặp', 'faq.php', '', 1, '2024-10-15 05:16:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `footer_sections`
--

CREATE TABLE `footer_sections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `footer_sections`
--

INSERT INTO `footer_sections` (`id`, `section_title`, `position`, `created_at`) VALUES
(4, 'Theo dõi', 1, '2024-10-15 03:45:44'),
(5, 'Giới thiệu', 2, '2024-10-15 03:45:44'),
(6, 'Công cụ', 3, '2024-10-15 03:45:44'),
(7, 'Hỗ trợ', 4, '2024-10-15 05:16:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `friend_id` int(11) DEFAULT NULL,
  `mutual_friends_count` int(11) DEFAULT 0,
  `status` enum('f-online','f-away','f-offline') DEFAULT 'f-offline',
  `status_add` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`, `mutual_friends_count`, `status`, `status_add`, `updated_at`) VALUES
(2, 2, 1, 5, 'f-offline', 'accepted', '2024-10-21 16:17:16'),
(7, 1, 2, 5, 'f-online', 'pending', '2024-10-21 16:00:19'),
(63, 1, 4, 5, 'f-offline', 'accepted', '2024-10-21 16:16:45'),
(64, 1, 5, 2, 'f-away', 'accepted', '2024-10-21 16:17:19'),
(65, 1, 5, 1, 'f-online', 'pending', '2024-10-21 16:00:19'),
(66, 1, 7, 4, 'f-offline', 'accepted', '2024-10-21 16:17:25'),
(75, 2, 1, 5, 'f-online', 'accepted', '2024-10-21 16:16:20'),
(76, 2, 1, 3, 'f-away', 'pending', '2024-10-21 16:00:19'),
(84, 2, 1, 1, 'f-offline', 'accepted', '2024-10-21 16:14:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `header_links`
--

CREATE TABLE `header_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `position` varchar(10) DEFAULT NULL,
  `is_logged_in` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `header_links`
--

INSERT INTO `header_links` (`id`, `title`, `url`, `position`, `is_logged_in`, `created_at`, `parent_id`) VALUES
(106, 'Home Pages', '', '1', 0, '2024-10-13 09:21:04', NULL),
(107, 'NAPA Default', 'index.php', '1.1', 0, '2024-10-13 09:21:04', '1'),
(108, 'Messenger', 'chat-messenger.php', '1.2', 0, '2024-10-13 09:21:04', '1'),
(109, 'Timeline', 'timeline.php', '2', 0, '2024-10-13 09:21:04', NULL),
(110, 'Timeline Photos', 'timeline-photos.php', '2.1', 0, '2024-10-13 09:21:04', '2'),
(111, 'Timeline Videos', 'timeline-videos.php', '2.2', 0, '2024-10-13 09:21:04', '2'),
(112, 'Timeline Groups', 'timeline-groups.php', '2.3', 0, '2024-10-13 09:21:04', '2'),
(113, 'Timeline Friends', 'timeline-friends.php', '2.4', 0, '2024-10-13 09:21:04', '2'),
(114, 'Timeline About', 'about.php', '2.5', 0, '2024-10-13 09:21:04', '2'),
(115, 'Forum', 'forum.php', '3', 0, '2024-10-13 09:21:04', NULL),
(116, 'Forum Create Topic', 'create_topic_forum.php', '3.1', 0, '2024-10-13 09:21:04', '3'),
(117, 'Forum Category', 'forum_category.php', '3.2', 0, '2024-10-13 09:21:04', '3'),
(118, 'Featured', 'featured.php', '4', 0, '2024-10-13 09:21:04', NULL),
(119, 'Messenger (Chatting)', 'chat-messenger.php', '4.1', 0, '2024-10-13 09:21:04', '4'),
(120, 'Notifications', 'notifications.php', '4.2', 0, '2024-10-13 09:21:04', '4'),
(121, 'Badges', 'badges.php', '4.3', 0, '2024-10-13 09:21:04', '4'),
(122, 'Faq\'s', 'faq.php', '4.4', 0, '2024-10-13 09:21:04', '4'),
(123, 'Account Setting', 'settingaccount.php', '5', 0, '2024-10-13 09:21:04', NULL),
(124, 'Privacy', 'privacy.php', '5.1', 0, '2024-10-13 09:21:04', '5'),
(125, 'Support & Help', 'support-and-help.php', '5.2', 0, '2024-10-13 09:21:04', '5'),
(126, 'Support Detail', 'support-and-help-detail.php', '5.3', 0, '2024-10-13 09:21:04', '5'),
(127, 'Support Search', 'support-and-help-search-result.php', '5.4', 0, '2024-10-13 09:21:04', '5'),
(128, 'Authentication', 'authentication.php', '6', 0, '2024-10-13 09:21:04', NULL),
(129, 'Login Page', 'login.php', '6.1', 0, '2024-10-13 09:21:04', '6'),
(130, 'Register Page', 'register.php', '6.2', 0, '2024-10-13 09:21:04', '6'),
(131, 'Logout Page', 'logout.php', '6.3', 0, '2024-10-13 09:21:04', '6'),
(132, 'Coming Soon', 'coming-soon.php', '6.4', 0, '2024-10-13 09:21:04', '6'),
(133, 'Tools', 'tools.php', '7', 0, '2024-10-13 09:21:04', NULL),
(134, 'Widgets', 'widgets.php', '7.1', 0, '2024-10-13 09:21:04', '7');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hobbies`
--

CREATE TABLE `hobbies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hobby_name` varchar(100) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hobbies`
--

INSERT INTO `hobbies` (`id`, `user_id`, `hobby_name`, `is_main`) VALUES
(1, 1, 'Photography', 1),
(2, 1, 'Traveling', 0),
(3, 1, 'Swimming', 0),
(4, 1, 'Surfing', 0),
(5, 1, 'Anime', 0),
(36, 1, 'Đọc sách', 0),
(37, 1, 'Chơi thể thao', 0),
(38, 2, 'Du lịch', 0),
(39, 2, 'Nấu ăn', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `liked_at`) VALUES
(14, 1, '2024-10-21 09:16:35'),
(15, 2, '2024-10-21 09:16:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('sent','delivered','seen') DEFAULT 'sent',
  `file_url` varchar(255) DEFAULT NULL,
  `message_type` enum('text','image','file','voice') DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `created_at`, `status`, `file_url`, `message_type`) VALUES
(1, 1, 2, 'alo', '2024-10-14 16:31:01', '2024-10-14 23:31:01', 'seen', NULL, 'text'),
(2, 1, 2, 'ê', '2024-10-15 03:31:01', '2024-10-15 10:31:01', 'seen', NULL, 'text'),
(3, 2, 1, 'ê', '2024-10-15 03:32:26', '2024-10-15 10:32:26', 'seen', NULL, 'text'),
(4, 2, 1, 'ê', '2024-10-15 03:32:28', '2024-10-15 10:32:28', 'seen', NULL, 'text'),
(5, 2, 1, 'alo', '2024-10-15 03:32:52', '2024-10-15 10:32:52', 'seen', NULL, 'text'),
(6, 1, 2, 'a', '2024-10-15 03:33:30', '2024-10-15 10:33:30', 'seen', NULL, 'text'),
(7, 1, 2, 'ê', '2024-10-15 03:37:56', '2024-10-15 10:37:56', 'seen', NULL, 'text'),
(8, 2, 1, 'sao thế\n', '2024-10-15 03:38:22', '2024-10-15 10:38:22', 'seen', NULL, 'text'),
(9, 1, 2, 'a', '2024-10-15 04:47:46', '2024-10-15 11:47:46', 'seen', NULL, 'text'),
(10, 2, 1, '1+1 = mấy\\n', '2024-10-15 04:57:19', '2024-10-15 11:57:19', 'seen', NULL, 'text'),
(11, 1, 2, '1+ 1 = 2', '2024-10-15 04:57:37', '2024-10-15 11:57:37', 'seen', NULL, 'text'),
(12, 1, 2, 'sai', '2024-10-15 07:08:21', '2024-10-15 14:08:21', 'seen', NULL, 'text'),
(13, 2, 1, 'hi keo', '2024-10-15 08:43:17', '2024-10-15 15:43:17', 'seen', NULL, 'text'),
(14, 1, 2, 'qlo', '2024-10-15 08:48:00', '2024-10-15 15:48:00', 'delivered', NULL, 'text'),
(15, 1, 2, 'qlo', '2024-10-15 08:48:02', '2024-10-15 15:48:02', 'delivered', NULL, 'text'),
(16, 1, 2, 'qlo', '2024-10-15 08:48:04', '2024-10-15 15:48:04', 'delivered', NULL, 'text'),
(17, 1, 2, 'qlo', '2024-10-15 08:48:10', '2024-10-15 15:48:10', 'delivered', NULL, 'text'),
(18, 1, 2, 'qlo', '2024-10-15 08:48:12', '2024-10-15 15:48:12', 'delivered', NULL, 'text'),
(19, 1, 2, 'uploads/files/6714e8c5ecdfa_bocchi.jpg', '2024-10-20 11:25:57', '2024-10-20 18:25:57', 'sent', 'uploads/files/6714e8c5ecdfa_bocchi.jpg', 'text'),
(20, 1, 2, 'uploads/images/6714e948bebeb.jpg', '2024-10-20 11:28:08', '2024-10-20 18:28:08', 'sent', 'uploads/images/6714e948bebeb.jpg', 'text'),
(21, 1, 2, 'uploads/files/6714e97fa6ef7_QtriHTTT_Lê Chính Đại_2105HTTB013_QLXTDH.docx', '2024-10-20 11:29:03', '2024-10-20 18:29:03', 'sent', 'uploads/files/6714e97fa6ef7_QtriHTTT_Lê Chính Đại_2105HTTB013_QLXTDH.docx', 'text'),
(22, 1, 2, 'uploads/images/6714f1a9d5458.jpeg', '2024-10-20 12:03:53', '2024-10-20 19:03:53', 'sent', 'uploads/images/6714f1a9d5458.jpeg', 'text'),
(23, 1, 2, 'uploads/images/6714f1d85c896.jpg', '2024-10-20 12:04:40', '2024-10-20 19:04:40', 'sent', 'uploads/images/6714f1d85c896.jpg', 'text'),
(24, 1, 2, 'a', '2024-10-20 12:04:44', '2024-10-20 19:04:44', 'sent', NULL, 'text'),
(25, 1, 2, 'a', '2024-10-20 12:04:48', '2024-10-20 19:04:48', 'sent', NULL, 'text'),
(26, 1, 2, 'uploads/files/6714f1ea9bf56_C# va Net Framework.pdf', '2024-10-20 12:04:58', '2024-10-20 19:04:58', 'sent', 'uploads/files/6714f1ea9bf56_C# va Net Framework.pdf', 'text'),
(27, 1, 2, 'uploads/files/6714f36243e0d_CSharp5_in_a_nutshell.pdf', '2024-10-20 12:11:14', '2024-10-20 19:11:14', 'sent', 'uploads/files/6714f36243e0d_CSharp5_in_a_nutshell.pdf', 'text'),
(28, 1, 2, 'a', '2024-10-20 15:10:27', '2024-10-20 22:10:27', 'sent', NULL, 'text'),
(30, 1, 2, 'def', '2024-10-20 15:12:20', '2024-10-20 22:12:20', 'sent', NULL, 'text'),
(31, 1, 2, 'abc', '2024-10-20 15:14:48', '2024-10-20 22:14:48', 'sent', NULL, 'text'),
(32, 1, 2, 'uploads/images/67152010aadd0.jpg', '2024-10-20 15:21:52', '2024-10-20 22:21:52', 'sent', 'uploads/images/67152010aadd0.jpg', 'text'),
(33, 1, 2, 'abc', '2024-10-20 15:27:28', '2024-10-20 22:27:28', 'sent', NULL, 'text'),
(34, 1, 2, 'abc', '2024-10-20 15:27:31', '2024-10-20 22:27:31', 'sent', NULL, 'text'),
(35, 1, 2, 'abc', '2024-10-20 15:27:33', '2024-10-20 22:27:33', 'sent', NULL, 'text'),
(36, 1, 2, 'abc', '2024-10-20 15:28:43', '2024-10-20 22:28:43', 'sent', NULL, 'text'),
(37, 1, 2, 'hi', '2024-10-20 15:29:10', '2024-10-20 22:29:10', 'sent', NULL, 'text'),
(38, 1, 2, 'uploads/files/671521cee6816_C# va Net Framework.pdf', '2024-10-20 15:29:18', '2024-10-20 22:29:18', 'sent', 'uploads/files/671521cee6816_C# va Net Framework.pdf', 'text'),
(39, 1, 2, 'uploads/files/671521d6c104a_Ebook_Csharp_DHCNTPHCM.rar', '2024-10-20 15:29:26', '2024-10-20 22:29:26', 'sent', 'uploads/files/671521d6c104a_Ebook_Csharp_DHCNTPHCM.rar', 'text'),
(40, 1, 2, 'uploads/files/671521e13ec9f_C# va Net Framework.pdf', '2024-10-20 15:29:37', '2024-10-20 22:29:37', 'sent', 'uploads/files/671521e13ec9f_C# va Net Framework.pdf', 'text'),
(41, 1, 2, 'uploads/files/671521ea2b090_Freetuts_cac_giai_phap_lap_trinh_CSharp.rar', '2024-10-20 15:29:46', '2024-10-20 22:29:46', 'sent', 'uploads/files/671521ea2b090_Freetuts_cac_giai_phap_lap_trinh_CSharp.rar', 'text'),
(42, 1, 2, 'uploads/images/6715220fb09e4.png', '2024-10-20 15:30:23', '2024-10-20 22:30:23', 'sent', 'uploads/images/6715220fb09e4.png', 'text'),
(43, 1, 2, 'uploads/images/671522d091d58.jpg', '2024-10-20 15:33:36', '2024-10-20 22:33:36', 'sent', 'uploads/images/671522d091d58.jpg', 'text'),
(44, 1, 2, 'test', '2024-10-20 15:37:54', '2024-10-20 22:37:54', 'sent', NULL, 'text'),
(45, 1, 2, 'uploads/images/671523d948356.png', '2024-10-20 15:38:01', '2024-10-20 22:38:01', 'sent', 'uploads/images/671523d948356.png', 'text'),
(46, 1, 2, 'uploads/images/67152497d50d5.jpg', '2024-10-20 15:41:11', '2024-10-20 22:41:11', 'sent', 'uploads/images/67152497d50d5.jpg', 'text'),
(47, 1, 2, 'aaa', '2024-10-20 15:41:26', '2024-10-20 22:41:26', 'sent', NULL, 'text'),
(48, 1, 2, 'uploads/images/67152535b3833.jpg', '2024-10-20 15:43:49', '2024-10-20 22:43:49', 'sent', 'uploads/images/67152535b3833.jpg', 'text'),
(49, 1, 2, 'uploads/images/6715273a4a429.png', '2024-10-20 15:52:26', '2024-10-20 22:52:26', 'sent', 'uploads/images/6715273a4a429.png', 'text'),
(50, 1, 2, 'aaaa', '2024-10-20 15:55:04', '2024-10-20 22:55:04', 'sent', NULL, 'text'),
(51, 1, 2, 'abc', '2024-10-20 16:02:24', '2024-10-20 23:02:24', 'sent', NULL, 'text'),
(52, 1, 2, 'abc', '2024-10-20 16:02:38', '2024-10-20 23:02:38', 'sent', NULL, 'text'),
(53, 1, 2, '', '2024-10-20 16:02:44', '2024-10-20 23:02:44', 'sent', 'uploads/images/671529a437a05.jpg', 'text'),
(54, 1, 2, '', '2024-10-20 16:03:26', '2024-10-20 23:03:26', 'sent', 'uploads/images/671529ce69103.png', 'text'),
(55, 1, 2, '', '2024-10-20 16:09:45', '2024-10-20 23:09:45', 'sent', 'uploads/images/67152b49deef1.png?1729440585', 'text'),
(56, 1, 2, '', '2024-10-20 16:13:44', '2024-10-20 23:13:44', 'sent', 'uploads/images/67152c38549dd.jpeg', 'text'),
(57, 1, 2, '', '2024-10-20 16:14:36', '2024-10-20 23:14:36', 'sent', 'uploads/images/67152c6c2a580.png', 'text'),
(58, 1, 2, 'phim hay', '2024-10-20 16:15:18', '2024-10-20 23:15:18', 'sent', NULL, 'text'),
(59, 1, 2, '', '2024-10-20 16:19:13', '2024-10-20 23:19:13', 'sent', 'uploads/images/67152d81ce2ca.png', 'text'),
(60, 1, 2, 'uploads/files/67152d8ddddfb_C# va Net Framework.pdf', '2024-10-20 16:19:25', '2024-10-20 23:19:25', 'sent', 'uploads/files/67152d8ddddfb_C# va Net Framework.pdf', 'text'),
(61, 1, 2, 'uploads/voice_messages/67152d9eb09f6.ogg', '2024-10-20 16:19:42', '2024-10-20 23:19:42', 'sent', 'uploads/voice_messages/67152d9eb09f6.ogg', 'text'),
(62, 1, 2, 'a', '2024-10-20 16:22:40', '2024-10-20 23:22:40', 'sent', NULL, 'text'),
(63, 1, 2, 'a', '2024-10-20 16:23:11', '2024-10-20 23:23:11', 'sent', NULL, 'text'),
(64, 1, 2, 'a', '2024-10-20 16:41:25', '2024-10-20 23:41:25', 'sent', NULL, 'text'),
(65, 1, 2, 'abc', '2024-10-21 08:15:15', '2024-10-21 15:15:15', 'sent', NULL, 'text'),
(66, 1, 2, 'a', '2024-10-21 08:17:42', '2024-10-21 15:17:42', 'sent', NULL, 'text'),
(67, 1, 2, 'uploads/files/67160e2b86395_C# va Net Framework.pdf', '2024-10-21 08:17:47', '2024-10-21 15:17:47', 'sent', 'uploads/files/67160e2b86395_C# va Net Framework.pdf', 'text'),
(68, 1, 2, '', '2024-10-21 08:22:07', '2024-10-21 15:22:07', 'sent', 'uploads/images/67160f2fc5a7d.jpg', 'text'),
(69, 1, 2, '', '2024-10-21 08:24:00', '2024-10-21 15:24:00', 'sent', 'uploads/images/67160fa023a10.png', 'text'),
(70, 1, 2, 'uploads/files/67160faf469d4_Lập trình C# 2008 cơ bản.pdf', '2024-10-21 08:24:15', '2024-10-21 15:24:15', 'sent', 'uploads/files/67160faf469d4_Lập trình C# 2008 cơ bản.pdf', 'text');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `newfeeds`
--

CREATE TABLE `newfeeds` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `published_at` datetime DEFAULT current_timestamp(),
  `location_lat` decimal(10,8) DEFAULT NULL,
  `location_lon` decimal(11,8) DEFAULT NULL,
  `visibility` enum('public','private','friends') DEFAULT 'public',
  `likes_count` int(11) DEFAULT 0,
  `dislikes_count` int(11) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `shares_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `newfeeds`
--

INSERT INTO `newfeeds` (`post_id`, `user_id`, `content`, `published_at`, `location_lat`, `location_lon`, `visibility`, `likes_count`, `dislikes_count`, `views_count`, `comments_count`, `shares_count`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2024-10-27 20:36:08', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-27 13:36:08', '2024-10-27 13:36:08'),
(13, 1, 'aaaa', '2024-10-27 21:07:53', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-27 14:07:53', '2024-10-27 14:07:53'),
(14, 1, 'aaaa', '2024-10-27 21:08:17', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-27 14:08:17', '2024-10-27 14:08:17'),
(15, 1, NULL, '2024-10-28 23:12:35', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 16:12:35', '2024-10-28 16:12:35'),
(16, 1, NULL, '2024-10-28 23:17:35', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 16:17:35', '2024-10-28 16:17:35'),
(50, 1, 'ahihi', '2024-10-29 00:12:24', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:12:24', '2024-10-28 17:12:24'),
(51, 1, 'ahihi', '2024-10-29 00:17:27', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:17:27', '2024-10-28 17:17:27'),
(52, 1, '', '2024-10-29 00:18:13', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:18:13', '2024-10-28 17:18:13'),
(53, 1, '', '2024-10-29 00:18:56', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:18:56', '2024-10-28 17:18:56'),
(54, 1, '', '2024-10-29 00:21:37', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:21:37', '2024-10-28 17:21:37'),
(55, 1, '', '2024-10-29 00:21:54', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:21:54', '2024-10-28 17:21:54'),
(56, 1, '', '2024-10-29 00:22:13', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:22:13', '2024-10-28 17:22:13'),
(57, 1, '', '2024-10-29 00:22:31', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:22:31', '2024-10-28 17:22:31'),
(58, 1, '', '2024-10-29 00:25:16', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:25:16', '2024-10-28 17:25:16'),
(59, 1, '', '2024-10-29 00:28:43', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:28:43', '2024-10-28 17:28:43'),
(60, 1, '', '2024-10-29 00:30:44', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:30:44', '2024-10-28 17:30:44'),
(61, 1, '', '2024-10-29 00:31:04', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:31:04', '2024-10-28 17:31:04'),
(62, 1, '', '2024-10-29 00:33:59', NULL, NULL, 'public', 0, 0, 0, 0, 0, '2024-10-28 17:33:59', '2024-10-28 17:33:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `created_at`, `is_read`) VALUES
(1, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 15:44:55', 1),
(2, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 15:49:20', 1),
(3, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 15:50:23', 1),
(4, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 15:54:59', 1),
(5, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 16:01:15', 1),
(6, 2, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-22 16:10:14', 0),
(7, 1, 'Có câu trả lời mới cho câu hỏi của bạn.', '2024-09-23 14:38:06', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Giới thiệu', 'Nội dung trang Giới thiệu...', 'about', '2024-09-10 08:32:32', '2024-09-10 08:32:32'),
(2, 'Chính sách bảo mật', 'Nội dung trang Chính sách bảo mật...', 'privacy', '2024-09-10 08:32:32', '2024-09-10 08:32:32'),
(3, 'Điều khoản dịch vụ', 'Nội dung trang Điều khoản dịch vụ...', 'terms', '2024-09-10 08:32:32', '2024-09-10 08:32:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires`, `created_at`) VALUES
(1, 1, 'd7086d963d9a750e92fe79645324543dc74a0184c0f927c4cdfe1a1f497b7d6911719bdf8418344156c8f51b3a338b030e13', 1725935133, '2024-09-10 01:55:33'),
(2, 1, '847c4525f04f56911e350895539d5c753dce5874d976bafd7247bc983a5e5e648de8e0876980e62e4e35363a1c3f70bd7491', 1725936114, '2024-09-10 02:11:54'),
(3, 1, '492d4120b777825879ede359475484b458130495f41a2c2fa0f0741e47c07e7902499bff5f8d424e4ac96d9a9f1bde7c99b3', 1725936130, '2024-09-10 02:12:10'),
(4, 1, '388b9bac723b12043438f20d7c7725160a7d2add495592facf97f4d6cea932163eee29cdd9e5d7e4d83897b72be5e8b6612f', 1725936319, '2024-09-10 02:15:19'),
(5, 1, '0c3ff5520e124a8d2304a051af7e1bf783e77ece91816196e58abce14926d9ec08060c001b398ad95faacdaee930d9fa165e', 1725936322, '2024-09-10 02:15:22'),
(6, 1, 'ea662602a4272f171b8dea399913f24fbb91956c192b77757dd74f34a246c78e82125f65484a4acb6c8025df1f02b6a31721', 1725936324, '2024-09-10 02:15:24'),
(7, 1, 'ef9abc537c72d9f1209f8a42263e9fe36eef0bb835851168000ec2e3d8158052d032ff8416d4f0452ca57023fa1e61632e5e', 1725937015, '2024-09-10 02:26:55'),
(8, 4, 'c460e42c090700c423f575beaffdf2556499320057e77cbe582e6e8538441bdd5a0bd22c495cd0ee01291b113ac3a6d2aa2b', 1725937053, '2024-09-10 02:27:33'),
(9, 4, '34fd898f66c98ae2b58f452e574dc0b67a92b2fe06b496b23fd81a2b99ef7b00c511e14824a0344992f033e3e18c5757a2dc', 1725937440, '2024-09-10 02:34:00'),
(10, 4, 'a79931bb792277a6fcb29f32a8e4ed8f398924dd7c7955c54db7de1faeec0dc9b96686b5dbb87c21856cb6c9c62156742bf8', 1725937895, '2024-09-10 02:41:35'),
(11, 4, '62a2e4f81798d3a109402a11ca750fa399d4db2561d67680b8742a8013ddc4375b72d2af1bd43276d8e9ce8d7d131a320b8e', 1725937918, '2024-09-10 02:41:58'),
(13, 4, '0c3e2c492c882c53a7801655e201b1552b1603bb5f2641873e3848e43ccebdf450da289309b9168f2a8a022dede2d15f845c', 1725938452, '2024-09-10 02:50:52'),
(14, 4, '1296b6ab42009867e86d1df3293c2a72043bbaf9165237f00e63496f2efa3090a49bbb612fd4552b578c73126529408b6792', 1725938462, '2024-09-10 02:51:02'),
(15, 1, 'f5152df48aff4ea3a097082fac02428810af8f6f3ba0246f20b325f2c45e519b8d5f6c589dbfff2249d929b3c47608ad2f8c', 1726412399, '2024-09-15 14:29:59'),
(16, 2, '964dfbcab02d21aa159118a8145af1b5aadad56b60734bd2e7c67ac951a13d110165e651e1c7cae0ed18be0f664a4f030ff1', 1726561288, '2024-09-17 07:51:28'),
(17, 2, '9f7a6997dbc06d09a08a2051c369d82c5dcd24dd910f3629129f5ad00929a69cbdcd2fe61956de987925d121fc0579d5aef2', 1726562156, '2024-09-17 08:05:56'),
(18, 2, 'ba66f0a065548ced3653124039f55fd43a63902f75ca5ffb83416699833d7f9fbe3bcd857a11c8800fabac57aeedfd1affe9', 1726562620, '2024-09-17 08:13:40'),
(19, 2, '53ee081926412e4e3ac1d99fb35b2cf98bb7290aa31cccf37f6e92bac12c5257b294268e5f86c6a79aac894bd42d98ebe5d0', 1726562883, '2024-09-17 08:18:03'),
(21, 4, '94776842f04d25d2191427f08899d7b4caeb1f017b40deb521b7f70d3cda8ff30dda95903a7bb3e6ae0b8a005c1fa43b99c8', 1726563686, '2024-09-17 08:31:26'),
(22, 2, '6b799c3cdf2ebc1020f005bbe5000fb3c55947ef3d36335d41729e1d52a0a3578f403c21b74195a3fcaa16ef1be629706f6f', 1727021228, '2024-09-22 15:37:08'),
(24, 2, '20fc6199f9ecaaf69f909ee88edbe605d3681685184c84afd4d04908d718f48dd82dc7f7f8bd3c2d93949ccc0b8f610c2e23', 1727406714, '2024-09-27 02:41:54'),
(25, 2, '54283a98bd2d7c60a0c9bd254931951f1cd6e3edc41026960bae3c65623f710a4333e17fa8b4d781d0d8e2930af83dd7650c', 1727406758, '2024-09-27 02:42:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `photos`
--

INSERT INTO `photos` (`id`, `user_id`, `photo_url`, `likes`, `created_at`) VALUES
(1, 1, 'http://example.com/photo1.jpg', 100, '2024-10-06 20:41:13'),
(2, 2, 'http://example.com/photo2.jpg', 50, '2024-10-06 20:41:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `postimages`
--

CREATE TABLE `postimages` (
  `image_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `postimages`
--

INSERT INTO `postimages` (`image_id`, `post_id`, `image_url`, `thumbnail_url`) VALUES
(1, 52, 'uploads/671fc755b1940_forward.png', NULL),
(2, 53, 'uploads/671fc780d62be_forward.png', NULL),
(3, 54, 'uploads/671fc821cbc60_forward.png', NULL),
(4, 55, 'uploads/671fc83264f03_forward.png', NULL),
(5, 56, 'uploads/671fc8456d374_forward.png', NULL),
(6, 57, 'uploads/671fc857a1a91_forward.png', NULL),
(7, 58, 'uploads/671fc8fca924a_forward.png', NULL),
(8, 59, 'uploads/671fc9cb1b8ab_forward.png', NULL),
(9, 60, 'uploads/671fca4485c70_forward.png', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `thread_id`, `user_id`, `content`, `created_at`) VALUES
(1, 2, 1, 'Database là gì?', '2024-09-19 06:44:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `profile_intro`
--

CREATE TABLE `profile_intro` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `about` text DEFAULT NULL,
  `fav_tv_show` text DEFAULT NULL,
  `favourit_music` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `profile_intro`
--

INSERT INTO `profile_intro` (`id`, `user_id`, `about`, `fav_tv_show`, `favourit_music`) VALUES
(1, 1, 'Hi, I am Đại, I am 24 years old and worked as a web developer in Napa', 'Sacred Games, Spartacus Blood, Game of Thrones', 'Justin Bieber, Shakira, Natti Natasha'),
(2, 2, 'Hello, I am Đại2, a graphic designer.', 'Breaking Bad, Money Heist', 'Beyoncé, Rihanna, Drake');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0,
  `thread_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `questions`
--

INSERT INTO `questions` (`id`, `title`, `content`, `user_id`, `created_at`, `views`, `thread_id`, `status`) VALUES
(1, 'Câu hỏi về lập trình C', 'Lập trình hướng đối tượng trong C có tính chất gì?', 1, '2024-09-15 01:00:00', 19, 1, '1'),
(2, 'Câu hỏi về tối ưu hóa SQL', 'Phân biệt Left join,Right join và Inner join', 2, '2024-09-15 02:00:00', 78, 2, '1'),
(3, 'Câu hỏi về cấu trúc dữ liệu', 'Database là gì?', 1, '2024-09-15 03:30:00', 36, 3, '1'),
(6, 'Câu hỏi về Database?', 'Database là gì?', 1, '2024-09-19 06:45:22', 4, 3, '1'),
(7, 'tại sao?', 'Phân tích thiết kế hướng chức năng?', 1, '2024-09-19 08:24:49', 0, 0, '2'),
(11, 'Câu hỏi về điểm', 'GPA 3.2 là học sinh gì?', 1, '2024-09-19 08:42:26', 4, 0, '1'),
(14, 'Câu hỏi về CLB?', 'Vào CLB được những lợi ích gì?', 1, '2024-09-22 13:22:36', 1, 5, '1'),
(15, 'Điểm bao nhiêu thì làm khóa luận?', 'Mng cho e hỏi bao nhiêu điểm thì được làm khóa luận vậy ạ', 1, '2024-09-23 08:01:16', 1, 4, '1'),
(16, 'Lỗi tùm lum?', 'php?', 1, '2024-09-23 14:36:45', 0, 4, '2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recent_links`
--

CREATE TABLE `recent_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recent_links`
--

INSERT INTO `recent_links` (`id`, `title`, `image`, `url`, `created_at`) VALUES
(1, 'Hướng dẫn lập trình C# cơ bản', 'images/resources/recentlink-1.jpg', 'https://example.com/lap-trinh-csharp', '2024-10-21 15:31:08'),
(2, 'Sự kiện lập trình tại TP.HCM', 'images/resources/recentlink-2.jpg', 'https://example.com/su-kien-lap-trinh', '2024-10-07 15:31:08'),
(3, 'Mẹo học lập trình hiệu quả', 'images/resources/recentlink-3.jpg', 'https://example.com/meo-hoc-lap-trinh', '2024-07-21 15:31:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` enum('question','answer') NOT NULL,
  `reference_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `content`, `type`, `reference_id`, `created_at`, `status`) VALUES
(2, 2, 'spam', 'question', 5, '2024-09-19 02:42:23', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `social_networks`
--

CREATE TABLE `social_networks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `platform_name` varchar(50) DEFAULT NULL,
  `profile_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `social_networks`
--

INSERT INTO `social_networks` (`id`, `user_id`, `platform_name`, `profile_url`) VALUES
(1, 1, 'Facebook', 'http://facebook.com/user1'),
(2, 2, 'Instagram', 'http://instagram.com/user2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `specialevents`
--

CREATE TABLE `specialevents` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_link` varchar(255) NOT NULL,
  `event_icon` varchar(255) NOT NULL,
  `event_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `specialevents`
--

INSERT INTO `specialevents` (`id`, `event_name`, `event_link`, `event_icon`, `event_class`) VALUES
(1, 'Sự kiện đêm Ocean Motel tại Columbia', '#', 'ti-gift', 'bg-purple'),
(2, 'Hội nghị Quốc tế lần thứ 3 năm 2016', '#', 'ti-microphone', 'bg-blue'),
(4, 'Lễ hội Văn hóa Dân gian Việt Nam', '#', 'ti-flag', 'bg-green');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stories`
--

CREATE TABLE `stories` (
  `story_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 24 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `stories`
--

INSERT INTO `stories` (`story_id`, `user_id`, `image_url`, `created_at`, `expires_at`) VALUES
(1, 1, '671fca58791a7_forward.png', '2024-10-28 17:31:04', '2024-10-29 17:31:04'),
(2, 1, '671fcb0771c68_forward.png', '2024-10-28 17:33:59', '2024-10-29 17:33:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `threads`
--

CREATE TABLE `threads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `threads`
--

INSERT INTO `threads` (`id`, `title`, `user_id`, `created_at`, `description`, `content`) VALUES
(1, 'Chủ đề về lập trình C', 1, '2024-09-14 02:00:00', NULL, ''),
(2, 'Chủ đề về tối ưu hóa SQL', 2, '2024-09-14 03:00:00', NULL, ''),
(3, 'Chủ đề về cấu trúc dữ liệu', 1, '2024-09-14 04:30:00', NULL, ''),
(4, 'Câu hỏi về Điểm?', 1, '2024-09-19 08:41:58', NULL, 'Làm thế nào để được làm Khóa luận tốt nghiệp?'),
(5, 'Câu Lạc Bộ', 1, '2024-09-22 13:21:25', NULL, 'Câu hỏi về CLB?');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `twitter_feed`
--

CREATE TABLE `twitter_feed` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `twitter_handle` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `twitter_feed`
--

INSERT INTO `twitter_feed` (`id`, `username`, `twitter_handle`, `message`, `timestamp`) VALUES
(1, 'Trương Đình Thi', '@trandthi', 'Hôm nay trời thật đẹp, mình cảm thấy rất vui!', '2024-10-21 22:45:53'),
(2, 'Phạm Văn An', '@phamvanan', 'Công việc hôm nay khá bận rộn nhưng rất thú vị.', '2024-10-21 22:45:53'),
(3, 'Trần Chiến', '@tranvchien', 'Mình vừa hoàn thành một dự án lớn, rất hài lòng với kết quả.', '2024-10-21 22:45:53'),
(4, 'Phạm Thị Thu Trang', '@Ptttrang', 'Chúc mọi người ngày mới vui vẻ và tràn đầy năng lượng!', '2024-10-21 22:45:53'),
(5, 'Lê Đại', '@ledai', 'Cuối tuần này mình dự định đi chơi xa cùng gia đình.', '2024-10-21 22:45:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `about` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `joined_date` date DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('online','offline','away') DEFAULT 'offline',
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `about`, `birthday`, `phone`, `blood_group`, `gender`, `country`, `occupation`, `joined_date`, `website`, `profile_picture`, `status`, `last_activity`) VALUES
(1, 'dai', '$2y$10$RdcWW03On2mW/etJtU6E8upC5N5bTrwFaYyvSoApwjmMOYuj8WDNS', 'dai98@gmail.com', '2024-09-09 04:38:48', 'Tôi là Đại. Tôi năm nay 21 tuổi. Tôi học ngành Hệ thống thông tin. Khoa tin học - ngoại ngữ', '2003-09-08', '0342751185', 'A', 'Nam', 'Hà Nội', 'Học viện Hành chính Quốc gia', '2024-10-01', NULL, 'uploads/profile_pictures/maki.jpeg', 'online', '2024-10-28 17:44:22'),
(2, 'dai2', '$2y$10$Yqwvo7k72/Xsgky8MbbbXOHU7ocvmGFtNKNFRemUl4ocr/ntaOuxm', 'lechinhdai98@gmail.com', '2024-09-09 13:09:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/profile_pictures/thor.jpeg', 'online', '2024-10-15 08:42:58'),
(4, 'dai23', '$2y$10$upEhhD7WMM28gNa/h72m4uUAr40jR5MmHIi5quc772H7SwstC6YSe', 'rellizisme98@gmail.com', '2024-09-10 02:27:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/profile_pictures/kafka.jpg', 'offline', '2024-10-14 15:18:39'),
(5, 'ledai123', '$2y$10$T7yA.lBfKjnuviz3PcW0sObQl9bf6K39XuVHYEgYsZR68yibjoKO6', 'onp88325@gmail.com', '2024-09-19 03:14:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'offline', '2024-10-13 14:16:30'),
(6, 'test1', '$2y$10$pte.HZrl4T/2bl6tVzN5ReCEPsuQAgU1/MbZf/67kr5YXbomfxe2S', 'dai123@gmail.com', '2024-09-27 01:32:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'offline', '2024-10-13 14:16:30'),
(7, 'test12', '$2y$10$eXXjbrp.bQgFrhv5VjjBoulemIC13RjAoS4p.popN.B3yIhp6uoUu', '1233@gmail.com', '2024-09-27 01:50:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'offline', '2024-10-13 14:16:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_posts`
--

CREATE TABLE `user_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lat` decimal(10,8) DEFAULT NULL,
  `lon` decimal(11,8) DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `posts` int(11) DEFAULT NULL,
  `followers` varchar(10) DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `joined` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `posts`, `followers`, `following`, `about`, `birthday`, `phone_number`, `blood_group`, `gender`, `country`, `occupation`, `joined`, `email`, `website`) VALUES
(1, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'lechinhdai98@gmail.com', 'http://localhost/forumNAPA/about.php'),
(2, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(3, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(4, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(5, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(6, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(7, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(8, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(9, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(10, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(11, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(12, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(13, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(14, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com'),
(15, 1, 5, '100', 50, 'Lập trình viên yêu thích công nghệ.', '1990-01-01', '0123456789', 'A+', 'Male', 'Việt Nam', 'Lập trình viên', '2023-01-01', 'user1@example.com', 'http://user1website.com'),
(16, 2, 10, '200', 75, 'Yêu thích du lịch và khám phá văn hóa.', '1992-02-02', '0987654321', 'B+', 'Female', 'Việt Nam', 'Nhà báo', '2023-01-02', 'user2@example.com', 'http://user2website.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `videos`
--

INSERT INTO `videos` (`id`, `user_id`, `video_url`, `likes`, `created_at`) VALUES
(1, 1, 'http://example.com/video1.mp4', 200, '2024-10-06 20:41:29'),
(2, 2, 'http://example.com/video2.mp4', 150, '2024-10-06 20:41:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `views`
--

INSERT INTO `views` (`id`, `user_id`, `viewed_at`) VALUES
(1, 1, '2024-10-21 09:16:35'),
(2, 2, '2024-10-21 09:16:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `work_experience`
--

CREATE TABLE `work_experience` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `work_experience`
--

INSERT INTO `work_experience` (`id`, `user_id`, `position`, `company_name`, `years_of_experience`) VALUES
(1, 1, 'Lập trình viên', 'Công ty A', 3),
(2, 2, 'Nhà báo', 'Công ty B', 5);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `active_users`
--
ALTER TABLE `active_users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `birthdays`
--
ALTER TABLE `birthdays`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `favorite_movies`
--
ALTER TABLE `favorite_movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Chỉ mục cho bảng `footer_sections`
--
ALTER TABLE `footer_sections`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Chỉ mục cho bảng `header_links`
--
ALTER TABLE `header_links`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Chỉ mục cho bảng `newfeeds`
--
ALTER TABLE `newfeeds`
  ADD PRIMARY KEY (`post_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `postimages`
--
ALTER TABLE `postimages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `profile_intro`
--
ALTER TABLE `profile_intro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `recent_links`
--
ALTER TABLE `recent_links`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `social_networks`
--
ALTER TABLE `social_networks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `specialevents`
--
ALTER TABLE `specialevents`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`story_id`);

--
-- Chỉ mục cho bảng `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `twitter_feed`
--
ALTER TABLE `twitter_feed`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_posts`
--
ALTER TABLE `user_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `work_experience`
--
ALTER TABLE `work_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `active_users`
--
ALTER TABLE `active_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- AUTO_INCREMENT cho bảng `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `birthdays`
--
ALTER TABLE `birthdays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `favorite_movies`
--
ALTER TABLE `favorite_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `footer_sections`
--
ALTER TABLE `footer_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT cho bảng `header_links`
--
ALTER TABLE `header_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT cho bảng `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `newfeeds`
--
ALTER TABLE `newfeeds`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `postimages`
--
ALTER TABLE `postimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `profile_intro`
--
ALTER TABLE `profile_intro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `recent_links`
--
ALTER TABLE `recent_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `social_networks`
--
ALTER TABLE `social_networks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `specialevents`
--
ALTER TABLE `specialevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `stories`
--
ALTER TABLE `stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `twitter_feed`
--
ALTER TABLE `twitter_feed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `user_posts`
--
ALTER TABLE `user_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `work_experience`
--
ALTER TABLE `work_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `newfeeds` (`post_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `favorite_movies`
--
ALTER TABLE `favorite_movies`
  ADD CONSTRAINT `favorite_movies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Các ràng buộc cho bảng `footer_links`
--
ALTER TABLE `footer_links`
  ADD CONSTRAINT `footer_links_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `footer_sections` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hobbies`
--
ALTER TABLE `hobbies`
  ADD CONSTRAINT `hobbies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `postimages`
--
ALTER TABLE `postimages`
  ADD CONSTRAINT `postimages_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `newfeeds` (`post_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `profile_intro`
--
ALTER TABLE `profile_intro`
  ADD CONSTRAINT `profile_intro_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `social_networks`
--
ALTER TABLE `social_networks`
  ADD CONSTRAINT `social_networks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `user_posts`
--
ALTER TABLE `user_posts`
  ADD CONSTRAINT `user_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `work_experience`
--
ALTER TABLE `work_experience`
  ADD CONSTRAINT `work_experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
