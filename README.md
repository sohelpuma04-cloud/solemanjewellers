# Soleman Jewellers – Enterprise Metal Calculator & Gold Loan Ledger (Node.js Full-Stack)

A professional, high-precision bilingual jewelry calculator and collateralized credit loan management suite built for **Soleman Jewellers**. Developed as a production-grade full-stack Node.js application utilizing React, TypeScript, Tailwind CSS, and Express.

## Key Features
*   **Bilingual Interface (English & Noto Sans Bengali):** Perfect for localized customer-facing operations.
*   **Precision Weight Translations:** Instand conversions between standard Grams and traditional Indian gold weights (**Vori, Ana, and Rati/Kuch**).
*   **Buy / Sell Calculator Point:** Calculates spot gold value based on alloy standards, computes customizable crafting surcharges (Percentage, Flat, or Per-gram), and aggregates 3% GST.
*   **Old Gold swaps (Old to New Asset Desk):** Computes old gold alloy melt weight yields, subtracts purification/casting wastage, and offsets values against desired item specs, settling with deficit fees or trade-in surplus refunds.
*   **Gold & Silver trading desk:** Real-time exchanges supporting custom presets (Nakmachi and Outright sell modes).
*   **Secure Gold Loan Ledger:** Log debt principals and repayment settlement lines inside a multi-transaction customer database. Automatically computes segment-by-segment monthly interest balances with customizable roundups.
*   **Archived Invoices History:** Review, print, and audit logs of saved bills and statement forms.
*   **Aesthetic Theme Palettes:** Instantly switch branding visual accent colors directly from the header.
*   **Local & Secure Server Settle Database Sync:** Automatically persists customer ledgers, pricing updates, and invoices both locally (`localStorage`) and backed up safely on the backend Node.js filesystem (`/data` storage).

---

## 🚀 How to Upload this Project to GitHub (Git Setup)

To upload this repository to your Git provider (GitHub, GitLab, Bitbucket, etc.), follow these commands in your machine's terminal:

1.  **Initialize Git Repository:**
    ```bash
    git init
    ```

2.  **Add all files to stage:**
    ```bash
    git add .
    ```

3.  **Create your baseline commit:**
    ```bash
    git commit -m "Initialize Soleman Jewellers Node.js full-stack system"
    ```

4.  **Connect your remote origin (Replace with your repository URI):**
    ```bash
    git remote add origin https://github.com/YOUR_USERNAME/soleman-jewellers-calculator.git
    ```

5.  **Rename main branch to main:**
    ```bash
    git branch -M main
    ```

6.  **Push to your repository:**
    ```bash
    git push -u origin main
    ```

*Note: The local `/data` folder containing dynamic prices and sensitive transaction backups is automatically configured to be ignored by our `.gitignore` to prevent data leaks when shared on public Git platforms.*

---

## 🛠️ Local Development & Running

Follow these simple instructions to install and spin up the Node.js production ecosystem locally:

### 1. Prerequisite
Ensure you have **Node.js (v18 or higher)** and **npm** installed on your workstation.

### 2. Install dependencies
Run this command inside the project's root folder:
```bash
npm install
```

### 3. Spin up development server
To run the Express backend API and Vite asset manager:
```bash
npm run dev
```
Open **`http://localhost:3000`** in your browser.

### 4. Build for Production
To bundle and compile high-perf assets for distribution:
```bash
npm run build
```
This produces optimized static assets in `/dist` and a fast, bundled Express server backend target file `dist/server.cjs`.

### 5. Launch Production Server
```bash
npm start
```
The server binds to port **`3000`** of your current machine.

---

## 📁 System Architecture Directory

*   `/server.ts` — The Node.js Express server defining secure prices, sync, and backup JSON endpoints.
*   `/src/App.tsx` — Central state manager for routing panels and themes.
*   `/src/types.ts` — TypeScript types representing invoices and credit ledger profiles.
*   `/src/locales.ts` — Translation dictionaries mapping English and Noto Sans Bengali.
*   `/src/utils/calculations.ts` — Calculation formulas for weights, wastage, taxes, and loan surcharge metrics.
*   `/src/components/` — Modular components (Header, BuySellTab, OldToNewTab, ExchangeTabs, LoanTab, HistoryTab, InvoiceModal).
*   `/data/` — Storage folder mapping local backups on the server (Automatically created on first run).
