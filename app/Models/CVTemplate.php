<?php

namespace App\Models;

use App\Http\Requests\StoreCVTemplateRequest;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CVTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'template',
        'title'
    ];
    public static function store(StoreCVTemplateRequest $request)
    {
        $data = $request->validated();
        $title = $data['title'];
        $htmlContent = "
        <html>
            <head>
                <titl>Template</title>
            </head>
            <body>
                <h1>Curriculum Vitae </h1>
                <h2>$title</h2>
            </body>
        </html>
        ";

        return response($htmlContent)->header('Content-Type', 'text/html');

        if ($request->hasFile('template')) {
            $filename = FileService::storeFile('templates/', $request->file('template'));
            $data['template'] = $filename;
        }
        return self::create($data);
    }
    public static function getAll()
    {
        $name = 'Kibom Welu';
        $htmlContent = "
        <html>
            <head>
                <title>Template</title>
            </head>
            <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333;'>

            <div style='width: 80%; margin: 30px auto; background-color: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
                <!-- Header -->
                <div style='text-align: center;'>
                <h1 style='margin: 0; font-size: 28px;'>{$name}</h1>
                <p style='margin: 5px 0;'>Email: jane.doe@email.com | Phone: +123-456-7890</p>
                <p style='margin: 5px 0;'>Location: New York, USA</p>
                </div>

                <!-- Summary Section -->
                <div style='margin-bottom: 20px;'>
                <h2 style='background-color: #007BFF; color: white; padding: 10px; margin: 0 -20px; font-size: 20px;'>Summary</h2>
                <div style='padding: 10px 0;'>
                    <p>
                    Motivated and detail-oriented IT graduate with strong foundational skills in software development, 
                    web design, and database management. Eager to apply academic knowledge to real-world problems 
                    and contribute to team success through hard work and collaboration.
                    </p>
                </div>
                </div>

                <!-- Experience Section -->
                <div style='margin-bottom: 20px;'>
                <h2 style='background-color: #007BFF; color: white; padding: 10px; margin: 0 -20px; font-size: 20px;'>Experience</h2>
                <div style='padding: 10px 0;'>
                    <ul style='list-style-type: none; padding: 0;'>
                    <li style='margin-bottom: 20px;'>
                        <h3 style='margin: 0 0 5px;'>Software Development Intern</h3>
                        <p style='margin: 0 0 5px;'>ABC Tech Solutions | June 2023 - August 2023</p>
                        <ul style='list-style-type: disc; margin-left: 20px;'>
                        <li>Developed front-end features for a client-facing web application using HTML, CSS, and JavaScript.</li>
                        <li>Collaborated with the team to design and implement RESTful APIs in Node.js.</li>
                        <li>Assisted in troubleshooting and debugging code issues, improving system performance.</li>
                        </ul>
                    </li>
                    <li>
                        <h3 style='margin: 0 0 5px;'>IT Assistant (Part-Time)</h3>
                        <p style='margin: 0 0 5px;'>XYZ University | September 2022 - May 2023</p>
                        <ul style='list-style-type: disc; margin-left: 20px;'>
                        <li>Provided technical support for students and staff, resolving hardware and software issues.</li>
                        <li>Managed IT inventory, ensuring proper maintenance and availability of resources.</li>
                        <li>Conducted workshops on basic programming concepts and web development.</li>
                        </ul>
                    </li>
                    </ul>
                </div>
                </div>

                <!-- Education Section -->
                <div style='margin-bottom: 20px;'>
                <h2 style='background-color: #007BFF; color: white; padding: 10px; margin: 0 -20px; font-size: 20px;'>Education</h2>
                <div style='padding: 10px 0;'>
                    <ul style='list-style-type: none; padding: 0;'>
                    <li>
                        <h3 style='margin: 0 0 5px;'>Bachelor of Science in Information Technology</h3>
                        <p style='margin: 0 0 5px;'>XYZ University | 2019 - 2023</p>
                        <p style='margin: 0;'>CGPA: 3.8/4.0</p>
                    </li>
                    </ul>
                </div>
                </div>
                <!-- Skills Section -->
                <div style='margin-bottom: 20px;'>
                <h2 style='background-color: #007BFF; color: white; padding: 10px; margin: 0 -20px; font-size: 20px;'>Skills</h2>
                <div style='padding: 10px 0;'>
                    <ul style='list-style-type: none; padding: 0; display: flex; flex-wrap: wrap;'>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>HTML/CSS</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>JavaScript</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>Vue.js</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>Node.js</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>MySQL</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>Git & GitHub</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>Agile Methodologies</li>
                    <li style='width: 48%; margin: 5px 1%; padding: 5px; background-color: #f0f0f0; border: 1px solid #ddd; text-align: center;'>Problem Solving</li>
                    </ul>
                </div>
                </div>
            </div>

            </body>
        </html>
        ";
        // return response($htmlContent)
        // ->header('Content-Type', 'text/html');
        $filename = 'user.html';
        Storage::put('public/templates/' . $filename, $htmlContent);
        // $filename = FileService::storeFile('templates/', $htmlContent);
        $file_link = url('/api/auth/cv-file') . '/' . $filename;

        return response()->json(['error' => false, 'filelink' => $file_link]);
        return self::get();
    }
}
