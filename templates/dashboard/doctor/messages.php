<?php
/**
 * Dashboard - Doctor Messages
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>
<div class="message-box">
    <div class="title-box">
        <h3>Messages</h3>
        <a href="add-listing.html" class="menu"><i class="icon-Dot-menu"></i></a>
    </div>
    <div class="chat-room">
        <div id="frame">
            <div id="sidepanel">
                <div class="side-title"><h3>Chats</h3></div>
                <div id="search">
                    <button><i class="far fa-search"></i></button>
                    <input type="text" placeholder="Search">
                </div>
                <div id="contacts">
                    <ul>
                        <li class="contact">
                            <div class="wrap">
                                <span class="contact-status online"></span>
                                <img src="assets/images/resource/chat-1.png" alt="">
                                <div class="meta">
                                    <h5>Rex Allen</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">12 min</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact active">
                            <div class="wrap">
                                <span class="contact-status away"></span>
                                <img src="assets/images/resource/chat-2.png" alt="">
                                <div class="meta">
                                    <h5>Bradshaw</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">4:32 PM</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact hidden-message">
                            <div class="wrap">
                                <span class="contact-status online"></span>
                                <img src="assets/images/resource/chat-3.png" alt="">
                                <div class="meta">
                                    <h5>Julia Jhones</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">1:40 PM</span>
                                    <span class="hidden-chat">2</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact">
                            <div class="wrap">
                                <span class="contact-status busy"></span>
                                <img src="assets/images/resource/chat-4.png" alt="">
                                <div class="meta">
                                    <h5>Anderson</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">9:20 AM/span&gt;
                                                        </span></div>
                            </div>
                        </li>
                        <li class="contact hidden-message">
                            <div class="wrap">
                                <span class="contact-status online"></span>
                                <img src="assets/images/resource/chat-5.png" alt="">
                                <div class="meta">
                                    <h5>Amelia Anna</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">1 day ago</span>
                                    <span class="hidden-chat">6</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact">
                            <div class="wrap">
                                <span class="contact-status away"></span>
                                <img src="assets/images/resource/chat-6.png" alt="">
                                <div class="meta">
                                    <h5>Samuel Daniels</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">2 days ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact">
                            <div class="wrap">
                                <span class="contact-status online"></span>
                                <img src="assets/images/resource/chat-7.png" alt="">
                                <div class="meta">
                                    <h5>Paolo Dybala</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">6/8/2020</span>
                                </div>
                            </div>
                        </li>
                        <li class="contact">
                            <div class="wrap">
                                <span class="contact-status online"></span>
                                <img src="assets/images/resource/chat-8.png" alt="">
                                <div class="meta">
                                    <h5>Mary Astor</h5>
                                    <p class="preview">Hey, How are you?</p>
                                    <span class="chat-time">31/7/2020</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content">
                <div class="contact-profile">
                    <img src="assets/images/resource/chat-2.png" alt="">
                    <div class="meta">
                        <h5>Bradshaw</h5>
                        <p>Stay at home, Stay safe</p>
                    </div>
                    <div class="chat-option">
                        <a href="messages.html"><i class="icon-phone"></i></a>
                        <a href="messages.html"><i class="icon-Video"></i></a>
                    </div>
                </div>
                <div class="messages">
                    <ul>
                        <li class="sent">
                            <img src="assets/images/resource/chat-2.png" alt="">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing sed.</p>
                            <span class="time">4:32 PM</span>
                            <p>Dolor sit amet consectetur</p>
                            <span class="time">4:30 PM</span>
                        </li>
                        <li class="replies clearfix">
                            <img src="assets/images/resource/chat-3.png" alt="">
                            <div class="text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing sed.</p>
                                <span class="time">4:40 PM</span>
                                <p>Dolor sit amet consectetur</p>
                                <span class="time">4:42 PM</span>
                            </div>
                        </li>
                        <li class="sent">
                            <img src="assets/images/resource/chat-2.png" alt="">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing sed.</p>
                            <span class="time">5:01 PM</span>
                            <p>Dolor sit amet consectetur</p>
                            <span class="time">5:03 PM</span>
                        </li>
                    </ul>
                </div>
                <div class="message-input">
                    <div class="wrap">
                        <input type="text" placeholder="Type something">
                        <i class="icon-Attatchment attachment" aria-hidden="true"></i>
                        <button class="submit"><i class="icon-Arrow-Right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>