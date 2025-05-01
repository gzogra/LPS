# Beyond the Benchmark: A Customizable Platform for Real-Time, Preference-Driven LLM Evaluation

## Web App Setup and Usage Guide

## Overview

This web application allows users to interact with multiple Large Language Models (LLMs), submit prompts, and receive evaluated responses through an internal judge LLM. It supports authorization for various APIs and provides a panel-based UI for managing prompts, parameters, and evaluations.

---

## 🌐 Online Demo Available

If you'd prefer to **skip the local setup process** and test the framework immediately, you can explore the app through our hosted **online demo** instance.

👉 [**Click here to try it now**](https://lps.dev-maister.gr/demo/)

This demo includes all core features and is pre-configured for immediate use with demo credentials. It's a convenient way to evaluate the functionality before deploying your own local instance.
> Under normal circumstances, this framework uses user authentication via a **MySQL database**, where individual user accounts are managed securely. However, for demonstration purposes, we have hardcoded a predefined username and password to simplify access.  
>  
> To log in during the demo, use the following credentials:  
> **Username:** `demo`  
> **Password:** `demo`

**Disclaimer:**  
> The code provided with this framework is intended strictly for **testing and research purposes**. It serves as a **proof of concept** and was not developed with full production-grade considerations such as coding standards, optimization, or strict security measures. While it effectively demonstrates the core functionality required for our research, it requires further refinement and polish before being used in a production environment or performance-critical context.
---

## Prerequisites and Local Setup

The app runs on **Apache and PHP**, and a convenient way to set it up locally is by using **XAMPP**, a free software package that includes Apache, PHP, and MySQL.

### 📁 Project Directory Structure

Below is the structure of the web application, along with brief descriptions of each file:
```
root/
├── index.php                   # Main entry point of the application (UI and controller)
├── style.css                   # CSS styling for the interface
├── authenticate.php            # Handles user login and API key verification
├── logout.php                  # Ends user sessions securely
├── ai.gif                      # Animated graphic used in the UI
├── favicon.ico                 # Icon displayed in the browser tab
└── main/
    └── ai/
        ├── api_1_v11_distilled.php         # General API utility functions
        ├── llm_api_v7_distilled.php        # Handles communication with selected LLMs
        ├── llm_judge_api_v1_distilled.php  # Processes evaluation via Judge LLM
    └── saved_datasets/
    └── images/
        ├── gears.gif
        ├── json.png  
```

### Technologies Required

- **XAMPP (Apache + PHP)**
- Web browser
- Internet access for API interactions

### Setup Instructions

#### Windows / macOS

1. **Download XAMPP:**
   - Visit [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
   - Download the Windows installer.

2. **Install XAMPP:**
   - Run the installer and follow the prompts.
   - Ensure Apache is selected during installation.

3. **Start Apache:**
   - Open the XAMPP Control Panel.
   - Click **Start** next to Apache.

4. **Deploy the App:**
   - Copy your web app files to the `htdocs` directory inside the XAMPP installation folder (e.g., `C:\xampp\htdocs\your_folder_name`).
   - Access the app via `http://localhost/your_folder_name` in your browser.

---

## LLM-PromptScope (LPS) Sections Overview

### 1. Large Language Models Panel

**Purpose:**  
This panel serves as the starting point for the app. It allows users to select one or more LLMs they wish to interact with. The selected models will receive the user's prompt and generate responses.

- `gpt4o-mini` (OpenAI)
- `Claude 3.5 Sonnet` (Anthropic)
- `Llama 3.2` (AI/ML)
- `Gemini 1.5 Pro` (AI/ML)

### 2. Authorization

To streamline access and interaction with multiple LLMs except OpenAI/Anthropic, we used an online API provider AI/ML (https://aimlapi.com/). This provider offers a unified interface that abstracts away the differences between various models' APIs, allowing us to call procedures and retrieve responses through a consistent and simplified workflow. By using this centralized service, we eliminate the need for separate integration logic for each model, making the app more maintainable, scalable, and easier for users to manage their credentials and usage across different providers. 

While the AI/ML API could theoretically serve as a single access point for all models, at the time of building this framework, it did not support every API we needed—such as OpenAI and Anthropic—so direct integration with those services remained necessary.

**Purpose:**  
Before interacting with any LLM, users must authenticate using their respective API keys. This panel ensures secure access to each model's capabilities.

- **OpenAI API Key** — for `gpt4o-mini` from https://auth.openai.com/log-in
- **Anthropic API Key** — for `Claude 3.5 Sonnet` from https://console.anthropic.com/
- **AI/ML API Key** — for `Llama 3.2` and `Gemini 1.5 Pro` from https://aimlapi.com/app/sign-up/


### 3. Prompt Panel

**Purpose:**  
This is the main input section where users craft their prompt. An additional field allows optional context to help models better understand or refine their responses.

- **Prompt** — primary input for the LLMs
- **Additional Context** — optional supporting content to enrich the main prompt

### 4. LLM Parameters

**Purpose:**  
This panel provides advanced control over how the LLMs respond. Users can fine-tune the output style and quality by setting key parameters.

- `max_tokens` — maximum output length
- `top_p` — nucleus sampling value
- `temperature` — randomness in response generation

### 5. Judge Panel

**Purpose:**  
This section introduces an automated evaluation process. Users select a judge LLM and provide it with criteria in the form of a prompt. The judge evaluates all model responses based on this input.

- Select a judging model from the available list
- Define a **Judge Prompt** that sets evaluation criteria

**Note:** The judge model's `temperature` is set to `0` and `max_tokens` is set to unlimited to ensure consistent and comprehensive evaluations.


## 🧠 LLM-Promptscope: Synthetic Dataset Creation Workflow

An additional feature of **LLM-Promptscope** is the **Synthetic Dataset Creation Workflow**, which allows users to generate curated prompt-response pairs tailored to their preferences. This tool is especially useful for research, fine-tuning, or evaluation scenarios.

---

### ⚙️ User Configurable Parameters

To initiate the workflow, the user must configure the following parameters:

- **LLM for Prompt Generation**: Model used to create same-domain variant prompts.
- **Number of Samples**: Total number of prompt-response pairs to be generated.
- **Initial Prompt Sample**: A base prompt used to seed the generation process.
- **Prompt Type**: Choose between:
  - `QA Pair` – question and answer format.
  - `Single Prompt` – standalone prompts without answers.

#### Judge Configuration

Additionally, the user must specify:

- **Judge LLM**: Model used to evaluate the quality of generated answers.
- **Judge Prompt**: Criteria or instruction set representing the user's preferences.
- **Minimum Score Threshold**: The minimum alignment score required to accept a sample.

---

### 🔁 Workflow Description

1. **Prompt Generation**:  
   The selected LLM generates a new prompt based on the same domain as the user-provided initial sample.

2. **Answer Generation**:  
   The same LLM then generates an answer to the newly created prompt.

3. **Judgment Phase**:  
   The response is passed to a Judge LLM, along with the judge prompt that defines evaluation criteria.  
   The judge returns an **alignment score** based on how well the answer meets the user's preferences.

4. **Filtering and Saving**:  
   If the score **meets or exceeds** the user-defined minimum threshold:
   - The `(generated_prompt, generated_answer, judge_score)` tuple is stored.
   - The sample is added to a growing **JSON file**.

5. **Completion and Download**:  
   After the specified number of samples is reached, the resulting dataset is presented to the user as a **downloadable JSON file**.

---

### 📄 Output Format

Each accepted entry in the JSON file will be structured as:

```json
{
  "prompt": "Generated prompt here",
  "answer": "Answer to the prompt",
  "score": 0.92
}
```

This workflow helps build high-quality, preference-aligned synthetic datasets automatically, combining LLM creativity with judgment-based filtering.
---

## Submit and Results

Once all sections are filled:

1. Click **Submit**.
2. The prompt and context are sent to the selected LLMs.
3. Responses are generated and passed to the Judge LLM.
4. **Results Panel** displays:
   - Each LLM's original response
   - The Judge LLM's evaluation of that response

---

## Logout

Click the **Logout** button to clear session data and exit securely.

---

## Notes

- Ensure all API keys are valid to avoid response errors.
- Internet access is required for live API calls.
- Judge evaluation is qualitative and depends on the prompt provided.

---

## ⚠️ Device Compatibility Notice

For the best user experience, we recommend using this framework on **desktop devices**. While the app may function on tablets or mobile browsers, the interface and performance are optimized for larger screens, full keyboard input, and desktop-class browsers.

Using a desktop ensures smoother navigation between panels, better visibility of multi-model responses, and more efficient management of prompts and API credentials.

--- 


