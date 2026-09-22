/**
 * Tigger : bahan_order_isi → bahan_order
 * bahan_order.total_qty = SUM(bahan_order_isi.qty)
 * bahan_order.subtotal = SUM(bahan_order_isi.jumlah)
 * bahan_order.total = bahan_order.subtotal + bahan_order.ongkir
*/

DELIMITER $$

CREATE TRIGGER trg_bahan_order_isi_after_insert
AFTER INSERT ON bahan_order_isi
FOR EACH ROW
BEGIN
    UPDATE bahan_order
    SET total_qty = (SELECT COALESCE(SUM(qty), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id),
        subtotal = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id),
        total = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id) + ongkir
    WHERE id = NEW.bahan_order_id;
END$$

CREATE TRIGGER trg_bahan_order_isi_after_update
AFTER UPDATE ON bahan_order_isi
FOR EACH ROW
BEGIN
    UPDATE bahan_order
    SET total_qty = (SELECT COALESCE(SUM(qty), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id),
        subtotal = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id),
        total = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id) + ongkir
    WHERE id = OLD.bahan_order_id;

    UPDATE bahan_order
    SET total_qty = (SELECT COALESCE(SUM(qty), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id),
        subtotal = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id),
        total = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = NEW.bahan_order_id) + ongkir
    WHERE id = NEW.bahan_order_id;
END$$

CREATE TRIGGER trg_bahan_order_isi_after_delete
AFTER DELETE ON bahan_order_isi
FOR EACH ROW
BEGIN
    UPDATE bahan_order
    SET total_qty = (SELECT COALESCE(SUM(qty), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id),
        subtotal = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id),
        total = (SELECT COALESCE(SUM(jumlah), 0) FROM bahan_order_isi WHERE bahan_order_id = OLD.bahan_order_id) + ongkir
    WHERE id = OLD.bahan_order_id;
END$$

DELIMITER ;