<!-- database structure -->

Tables: users, transactions, wallets, scheduled payments, notifications

users (
user_id, (int) (Primary key) (auto increment)
email, (varchar(255)) (unique)
phone, (varchar(20)) (unique)
password, (varchar(255))
username, (varchar(255))
role, (enum('user','admin')) (default user)
verification_status, (enum('pending','approved','rejected'))
document, (varchar(255))
created_at
)

wallets (
wallet_id, (primary key)
user_id, (foreign key)
balance, (decimal(15,2))
created_at,
)

transactions (
transaction_id, (primary key)
wallet_id, (foreign key)
amount, (decimal(15,2))
transaction_type, (enum('deposit','withdrawal','transfer','qr_payment))
reference_id, (put another transactions id to link them like if its a peer to peer put the transaction id of the sender in the reference id of the reciever)
created_at
)

notifications (
notification_id, (primary key)
user_id, (foreign key)
message,
status, (enum('unread','read'))
created_at
)

scheduled_payments (
scheduled_id, (primary key)
wallet_id, (foreign key)
amount, (decimal)
recipient_wallet_id,(foreign key)
schedule_type, (enum('one time','daily','weekly','monthly'))
next_run, (timestamp for when must the next transaction happen)
status, (enum('active','completed','cancelled'))
created_at
)

                                           _ _ _ _ _ _ _1 to many------->scheduled_payments
                                          |
                                          |

users-----------1 to many---------->wallets-------1 to many------->transactions
|
|\_ \_ \_ \_ \_ \_1 to many---------->notifications

<!-- database structure end -->
