# ⚙️ ProcureFlow: Enterprise Procure-to-Pay System

ProcureFlow is a sophisticated, data-driven workflow engine designed to automate the complete procurement lifecycle—eliminating manual bottlenecks from initial request to final vendor payment.

---

## 🏗 System Architecture

To help you understand how ProcureFlow handles complex business logic, we've broken down the architecture into three primary perspectives:

### 1. The Core Lifecycle (Business Process)
This diagram illustrates the journey of a single Purchase Requisition (PR) as it transforms into a closed payment.

```mermaid
graph TD
    %% Entities
    Req((Requester))
    Vend((Vendor))
    Dept[Dept Head]
    IT[Admin / IT]
    Acc[Accounts Team]

    %% Process
    Req -->|1. Create PR| PR[Purchase Requisition]
    PR -->|2. Upload Quotes| Quot{Quotation Pool}
    System[ProcureFlow Engine] -.->|Auto-Select Lowest| Quot
    Quot -->|3. Submit| Stage1[Stage 1: Compliance]
    Stage1 -->|Approve| Stage2[Stage 2: IT Approval]
    
    Stage2 -->|4. Generate| PO[Purchase Order PDF]
    PO -->|Auto-Email| Vend
    
    Vend -->|5. Secure Link| Inv[Invoice Upload]
    Inv -->|6. Review| Acc
    Acc -->|7. Mark Paid| Done((Workflow Closed))

    %% Styling
    style Done fill:#059669,color:#fff
    style PO fill:#3b82f6,color:#fff
    style Stage1 fill:#f59e0b,color:#fff
    style Stage2 fill:#f59e0b,color:#fff
```

### 2. Data & Relationship Model
How the system maintains data integrity across different entities.

```mermaid
erDiagram
    PURCHASE-REQUISITION ||--o{ QUOTATION : "has multiple"
    PURCHASE-REQUISITION ||--o| PURCHASE-ORDER : "becomes"
    PURCHASE-ORDER ||--o| INVOICE : "receives"
    PURCHASE-REQUISITION }|--|| DEPARTMENT : "belongs to"
    PURCHASE-REQUISITION ||--o{ APPROVAL-LOG : "tracks"
    USER ||--o{ AUDIT-LOG : "generates"
    VENDOR ||--o{ QUOTATION : "provides"
```

### 3. Security & Role Permissions
ProcureFlow uses a strict Role-Based Access Control (RBAC) system.

```mermaid
graph LR
    subgraph Access Levels
        SA[Super Admin] ---|Full System Control| Core[Auth & Audit]
        AD[Admin] ---|Users & Depts| Mgmt[Management]
        AC[Accounts] ---|Payments & Invoices| Fin[Financial]
        AP[Approvers] ---|Logic Gates| Flow[Workflows]
        RQ[Requesters] ---|Data Entry| Entry[PR Creation]
    end
```

---

## 🌟 Key Capabilities

- **⚡ Zero-Login Vendor Portal**: Vendors interact with the system via unique, secure tokens. No passwords required for them to upload invoices.
- **🛡️ Multi-Stage Guardrails**: PRs cannot bypass approval stages. The system enforces compliance at every step.
- **📧 Automated Notifications**: Built-in integration with **Brevo (SMTP)** for instant alerts to Requesters, Approvers, and Vendors.
- **📄 Pro-Grade PDF Engine**: Generates clean, professional Purchase Orders with unique tracking numbers and secure links.
- **🔍 Forensic Audit Logs**: Every click is recorded. Know exactly who approved, rejected, or modified any record at any time.

---

## 🛠 Tech Stack

- **Backend**: Laravel 12 (Modern PHP)
- **Database**: SQLite (Optimized for persistence and speed)
- **Mailing**: Brevo SMTP Relay
- **Design**: Tailwind CSS & Alpine.js (Lightweight & Reactive)
- **Diagramming**: Mermaid.js Integrated

---

## 🚀 Getting Started

1.  **Clone**: `git clone https://github.com/zaidinabeel/po_workflow.git`
2.  **Install**: `composer install && npm install && npm run build`
3.  **Config**: `cp .env.example .env && php artisan key:generate`
4.  **Database**: `php artisan migrate --seed`
5.  **Run**: `php artisan serve`

## 📄 Deployment
For hosting on a VPS or **Hostinger**, see our [Step-by-Step Deployment Guide](deployment_guide.md).
