# ⚙️ ProcureFlow: Procure-to-Pay Workflow System

ProcureFlow is a robust, data-driven workflow management system built to streamline the procurement process—from initial requisition to final payment acknowledgement.

## 🏗 System Architecture

```mermaid
graph TD
    Requester((Requester)) -->|Create PR| PR[Purchase Requisition]
    PR -->|Request Quotations| Vendor((Vendor))
    Vendor -->|Upload| Quot[Quotations]
    
    subgraph "Intelligent Selection"
        System[ProcureFlow Engine] -->|Auto-select lowest price| Quot
        Requester -->|Validate / Manual Override| Quot
    发挥
    
    Quot -->|Submit Approval| Stage1[<b>Stage 1 Approval</b><br/>Dept Head / Compliance]
    Stage1 -->|Approve| Stage2[<b>Stage 2 Approval</b><br/>Admin / IT Dept]
    
    Stage2 -->|Approve| PO[<b>Purchase Order</b><br/>PDF Generated]
    PO -->|Auto-Email PDF| Vendor
    
    Vendor -->|Secure Link| Inv[<b>Invoice Upload</b>]
    Inv -->|Notification| Accounts((Accounts Team))
    
    Accounts -->|Bank Payment| Paid[<b>Mark as Paid</b>]
    Paid -->|Auto-Email Receipt| Vendor
```

## 🌟 Key Features

- **Multi-Stage Approvals**: Custom workflow paths ensuring hierarchical compliance.
- **Automated PO Generation**: High-quality PDF generation for purchase orders.
- **Secure Vendor Portal**: Unique, token-based links allowing vendors to upload invoices without system login.
- **Smart Quotation Pricing**: Automated identification of the most cost-effective vendors.
- **Email Notifications**: Real-time Brevo-integrated alerts for every status change.
- **Audit Logging**: Comprehensive tracking of "who did what and when."
- **Super Admin Panel**: Full control over users, departments, and system security.

## 🛠 Tech Stack

- **Framework**: [Laravel 12+](https://laravel.com)
- **Database**: SQLite (Default) / MySQL Compatible
- **Frontend**: Tailwind CSS & Alpine.js
- **PDF Engine**: Barryvdh DomPDF
- **Mailing**: Brevo (SMTP Relay)
- **Logging**: Custom Polymorphic Audit Logs

## 🚀 Getting Started

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/zaidinabeel/po_workflow.git
    ```
2.  **Install Dependencies**:
    ```bash
    composer install
    npm install && npm run build
    ```
3.  **Setup Environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Run Migrations & Seed**:
    ```bash
    php artisan migrate --seed
    ```
5.  **Serve**:
    ```bash
    php artisan serve
    ```

## 📄 Documentation
For detailed hosting and production setup instructions, refer to the [Deployment Guide](deployment_guide.md).
