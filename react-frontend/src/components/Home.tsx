import { useEffect, useState } from "react";

interface Categoria {
  id: number;
  nome: string;
}

interface Evento {
  id: number;
  titolo: string;
  descrizione?: string;
  data_evento?: string;
  categoria?: Categoria;
  numeroIscritti?: number;
  massimo_partecipanti?: number;
  img_path?: string;
  video_path?: string;
  prezzo?: number;
}

interface ApiResponse {
  eventi: Evento[];
  pagina_corrente: number;
  totale_pagine: number;
  categorie: Categoria[];
  categoria_selezionata: number | null;
}

export default function Home() {
  const [eventi, setEventi] = useState<Evento[]>([]);
  const [categorie, setCategorie] = useState<Categoria[]>([]);
  const [loading, setLoading] = useState(true);
  const [paginaCorrente, setPaginaCorrente] = useState(1);
  const [totalePagine, setTotalePagine] = useState(1);
  const [categoriaSelezionata, setCategoriaSelezionata] = useState<number | null>(null);
  const [error, setError] = useState<string | null>(null);

  const loadData = (pagina: number, categoriaId: number | null) => {
    setLoading(true);
    setError(null);

    let url = `http://localhost:8000/api/home?pagina=${pagina}`;
    if (categoriaId !== null) {
      url += `&categoria=${categoriaId}`;
    }

    fetch(url)
      .then(async (res) => {
        if (!res.ok) {
          const text = await res.text();
          throw new Error(`HTTP error! status: ${res.status} - ${text}`);
        }
        return res.json();
      })
      .then((data: ApiResponse) => {
        setEventi(data.eventi);
        setCategorie(data.categorie);
        setPaginaCorrente(data.pagina_corrente);
        setTotalePagine(data.totale_pagine);
        setCategoriaSelezionata(data.categoria_selezionata);
        setLoading(false);
      })
      .catch((e) => {
        setError(e.message);
        setLoading(false);
      });
  };

  useEffect(() => {
    loadData(1, null);
  }, []);

  const handleCategoriaChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const val = e.target.value;
    const id = val === "" ? null : parseInt(val);
    setCategoriaSelezionata(id);
    setPaginaCorrente(1);
    loadData(1, id);
  };

  const handlePageChange = (nuovaPagina: number) => {
    if (nuovaPagina < 1 || nuovaPagina > totalePagine) return;
    setPaginaCorrente(nuovaPagina);
    loadData(nuovaPagina, categoriaSelezionata);
  };

  if (loading) return <p>Caricamento...</p>;
  if (error) return <p style={{ color: "red" }}>Errore: {error}</p>;

  return (
    <div>
      <h1>Home</h1>

      <h2>Filtra per categoria</h2>
      <select value={categoriaSelezionata ?? ""} onChange={handleCategoriaChange}>
        <option value="">-- Tutte --</option>
        {categorie.map((cat) => (
          <option key={cat.id} value={cat.id}>
            {cat.nome}
          </option>
        ))}
      </select>

      <h2>Eventi</h2>
      {eventi.length === 0 ? (
        <p>Nessun evento disponibile.</p>
      ) : (
        <div style={{ display: "flex", flexWrap: "wrap", gap: "1rem" }}>
          {eventi.map((ev) => (
            <div
              key={ev.id}
              style={{
                border: "1px solid black",
                borderRadius: "0.5rem",
                padding: "1rem",
                width: "300px",
              }}
            >
              <h3>{ev.titolo}</h3>
              {ev.data_evento && <p><strong>Data evento:</strong> {ev.data_evento}</p>}
              {ev.categoria && <p><strong>Categoria:</strong> {ev.categoria.nome}</p>}
              {(ev.numeroIscritti !== undefined && ev.massimo_partecipanti !== undefined) && (
                <p>Iscritti: {ev.numeroIscritti} / {ev.massimo_partecipanti}</p>
              )}
              {ev.img_path ? (
                <img
                  src={ev.img_path.startsWith("/") ? ev.img_path : "/" + ev.img_path}
                  alt="Immagine Evento"
                  width={100}
                />
              ) : (
                <p>Nessuna immagine</p>
              )}
              {ev.video_path ? (
                <p>
                  <a
                    href={ev.video_path.startsWith("/") ? ev.video_path : "/" + ev.video_path}
                    target="_blank"
                    rel="noreferrer"
                  >
                    Guarda Video
                  </a>
                </p>
              ) : (
                <p>Nessun video</p>
              )}
              {ev.prezzo !== undefined && (
                <p>
                  <strong>Prezzo:</strong> € {ev.prezzo.toFixed(2).replace(".", ",")}
                </p>
              )}
              <a href={`/eventi/show/${ev.id}`}>Visualizza</a>
            </div>
          ))}
        </div>
      )}

      <div style={{ marginTop: "2rem" }}>
        <p>
          Pagina {paginaCorrente} di {totalePagine}
        </p>
        <nav>
          <button onClick={() => handlePageChange(1)} disabled={paginaCorrente === 1}>
            Prima pagina
          </button>
          <button onClick={() => handlePageChange(paginaCorrente - 1)} disabled={paginaCorrente === 1}>
            Precedente
          </button>
          <button onClick={() => handlePageChange(paginaCorrente + 1)} disabled={paginaCorrente === totalePagine}>
            Successiva
          </button>
          <button onClick={() => handlePageChange(totalePagine)} disabled={paginaCorrente === totalePagine}>
            Ultima pagina
          </button>
        </nav>
      </div>
    </div>
  );
}
