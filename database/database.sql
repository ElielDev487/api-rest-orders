USE api_test;
CREATE TABLE orders(
    id int primary key auto_increment,
    num_cmd varchar (15) not null unique, -- ex: CMD-2026-0001
    nom_client varchar(100) not null,
    phone varchar(20) not null,
    delivery boolean default false,
    montant decimal(10, 2) not null,
    statut enum('pending', 'confirmed', 'cancelled') default 'pending',
    created_at timestamp default current_timestamp
);