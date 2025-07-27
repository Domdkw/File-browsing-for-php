

This project is a PHP-based file browsing tool with the following features:

### Introduction
This is a lightweight PHP project for browsing files and folders on a server. It provides a clean front-end interface that displays directory contents and supports navigation and quick link functionality.

### Features
- **List subdirectories and files**: Through the interface provided by `api.php`, it can recursively list all subdirectories and files under the specified directory.
- **User-friendly front-end interface**: A visually appealing and easy-to-use interface is provided using `index.html` and `style.css`.
- **Quick access links**: The page includes quick links that allow users to rapidly navigate to commonly used directories.
- **Icon support**: SVG icons (such as Bilibili, GitHub, etc.) are included to enhance the visual experience of the interface.

### Usage Instructions
1. **Deployment environment**: Ensure that the server supports PHP, and place the project files into the server's web root directory or a specified directory.
2. **Access the page**: Open `index.html` in a browser to view the file browsing interface.
3. **Configure the API**: Modify the directory path in `api.php` to specify the file directory you wish to browse.
4. **Use the features**: Browse files and folders through the navigation bar and file list, and use the quick links to jump to specific directories.

### Directory Structure
- `api.php`: PHP file that provides the API interface for listing files and directories.
- `index.html`: Front-end page that displays the file browsing content.
- `style.css`: Style sheet that controls the appearance of the front-end interface.
- `icon/`: Directory containing SVG icon resources.

### Open Source License
This project follows an open source license agreement. For details, please refer to the `LICENSE` file.

### Contributors
- Domdkw
- Richmond

If you need further feature customization or interface optimization, please refer to the code and modify it according to your needs. Code contributions and improvement suggestions are welcome!