# Learning Journal System Installation

This project is created for the DOST Training-related programs for employees. It features a comprehensive user management for **administrators**, a training management for the **HR**, and document management for the **users**.

To use this, install dependencies first. Run the ***following commands***:

```bash
# Run as administrator...
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```
 - This will install PHP and Composer to your system which is required by this project.

```bash
composer global require laravel/installer
```
 - This will run the Laravel installation on your system.

```bash
git pull https://github.com/ErickGaceta/LearningJournalSystem.git
```
 - This will pull the source code of the project to the system
Open the project folder on any Text Editor, though **VS Code** is recommended.

## Install node
```
https://nodejs.org/en/download
```
 - You can choose the node installer or the standalone version.

**!Important! Run these**
```npm
npm install
```
 - This will install the node dependencies needed by the project.

```npm
npm run build
```
 - This will build the project CSS and JS and output them to ``` public/ ``` directory

```composer
composer install
```
 - This will install all the Laraven dependencies

### This project is created with Laravel/Livewire, TailwindCSS, Heroicons, and Flux
Links to them here:
```links
 - LARAVEL - https://laravel.com/
 - LIVEWIRE - https://livewire.laravel.com/
 - HEROICONS - https://heroicons.com/
 - TAILWIND CSS - https://tailwindcss.com/
 - FLUX UI - https://fluxui.dev/
```

**Other Links**

```links
TCPDF - https://tcpdf.org/
```

# Features

## Admin

### Dashboard

 - Summarizes active training and total users.

### User Creation and Management

 - Create, edit, and delete users/HR.

### Position Creation and Management

 - Create, edit, and delete positions assignable to users/HR.
   
### Division Creation and Management

 - Create, edit, and delete divisions assignable to users/HR.

## HR

### Dashboard

 - Overview of the active modules and total modules for training.

### Training Module Creation

 - Create, edit, and delete Training modules that can be assigned to users.

### User Training Assignment

 - Assign the created training module to user/s.


## Users

### Dashboard

 - Overview of active trainings and total documents created

### Assigned Training Management

 - View past, current, and future training assignments.

### Document Management

 - Create documents linked to the project to be printed as a PDF.
