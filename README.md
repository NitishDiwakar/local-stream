---

# local-stream

A minimal LAN-based file drop and streaming utility built with PHP.

**local-stream** allows you to upload, stream, download, and delete files locally over your network without relying on any third-party services.

This project is designed to be lightweight, dependency-free, and easy to deploy on localhost using XAMPP, Apache, or any basic PHP server.

---

## Features

- Drag & drop file upload  
- Live upload progress bar  
- File type restriction (extension-based)  
- File size display  
- Thumbnail preview for images  
- Metadata-based preview for videos  
- Byte-range video streaming (HTTP 206 Partial Content)  
- Instant seeking support  
- Download button  
- Delete individual files  
- Clear all files option  
- Hidden file filtering (`.gitignore`, `.htaccess` excluded)  
- Responsive dark theme UI  
- Mobile friendly (tested on older Android devices)  
- No database required  
- No external libraries  
- No cloud dependency  

---

## Setup

1. Place the project folder inside your web server directory.  
   Example (XAMPP):

htdocs/local-stream/

2. Ensure `uploads/` directory exists.

3. Add `.gitignore` inside `uploads/` to keep the folder tracked:


!.gitignore

4. Access via browser:

http://localhost/local-stream/

or

http://your-local-ip/local-stream/

5. If uploading large files, increase `php.ini` limits:

upload_max_filesize post_max_size

---## Setup

1. Place the project folder inside your web server directory.  
   Example (XAMPP):

htdocs/local-stream/

2. Ensure `uploads/` directory exists.


3. Access via browser:

http://localhost/local-stream/

or

http://your-local-ip/local-stream/

4. If uploading large files, increase `php.ini` limits:

upload_max_filesize post_max_size

---

## Streaming Implementation

Video playback is handled through a custom PHP streaming engine.

- Supports HTTP Range requests  
- Returns `206 Partial Content`  
- Uses `fseek()` for byte-level access  
- Streams in small chunks (low memory usage)  
- Allows instant seeking  
- Does not preload entire file  

This enables efficient playback of large files over LAN without downloading them fully to the client device.

---

## Use Case

Designed for temporary file transfer and streaming over local network:

Send → Stream / Download → Delete → Done

Ideal for:

- Storage-limited devices  
- Old Android phones  
- Quick media testing  
- Private LAN file sharing  
- Lightweight home streaming  

---

## Requirements

- PHP 7+  
- Apache (or any PHP server)  
- Browser with HTML5 video support  
- Same local network for cross-device access  

---


## Security Notes

- Uses `basename()` to prevent directory traversal  
- Hidden files are excluded from listing  
- Extension filtering enforced  
- Optional MIME validation can be added  
- For public deployment, additional hardening is recommended  

---

## Design Philosophy

local-stream is intentionally minimal.

No accounts.  
No database.  
No indexing.  
No background services.  
No cloud.  
No bloat.  

Just file transfer and byte-range streaming done correctly.

---

## License

Free to use and modify.

---

## Author

**Nitish Kumar Diwakar**


---
