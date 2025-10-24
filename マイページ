/*
MyPage React component for "アンケート + ポイント交換" Web App
Created based on the uploaded spec (system structure, features, screen flow) in the user's PPTX. See conversation for citation.

How to use:
1. Place this file in a React project (Vite or Next.js). Tailwind CSS should be configured.
2. Import and render <MyPage /> on the route /mypage or inside your app layout.
3. The component expects the following REST endpoints (example):
   - GET  /api/user/me             -> { id, name, points, avatar }
   - GET  /api/surveys             -> [ { id, title, answeredAt, points } ]
   - GET  /api/exchanges           -> [ { id, title, cost } ]
   - POST /api/exchange            -> { exchangeId }
   - GET  /api/notifications       -> [ { id, message, date } ]
   - POST /api/logout

This component is a UI-first implementation. Replace `fetch*()` mocks with real API calls.
*/

import React, { useEffect, useState } from 'react';

export default function MyPage() {
  const [user, setUser] = useState(null);
  const [surveys, setSurveys] = useState([]);
  const [exchanges, setExchanges] = useState([]);
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Replace these with real API calls in your project
    async function fetchAll() {
      try {
        setLoading(true);
        const userData = await mockFetchUser();
        const surveysData = await mockFetchSurveys();
        const exchangesData = await mockFetchExchanges();
        const notes = await mockFetchNotifications();
        setUser(userData);
        setSurveys(surveysData);
        setExchanges(exchangesData);
        setNotifications(notes);
      } catch (e) {
        setError('読み込みに失敗しました');
      } finally {
        setLoading(false);
      }
    }
    fetchAll();
  }, []);

  if (loading) return <div className="p-6">読み込み中...</div>;
  if (error) return <div className="p-6 text-red-600">{error}</div>;

  return (
    <div className="min-h-screen bg-slate-50 p-6">
      <header className="max-w-4xl mx-auto flex items-center justify-between">
        <div className="flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-xl">{user?.name?.[0] ?? 'U'}</div>
          <div>
            <div className="text-lg font-semibold">{user?.name}</div>
            <div className="text-sm text-slate-600">ユーザーID: {user?.id}</div>
          </div>
        </div>
        <div className="flex items-center gap-4">
          <div className="text-right">
            <div className="text-xs text-slate-500">保有ポイント</div>
            <div className="text-2xl font-bold">{user?.points} pt</div>
          </div>
          <button
            className="px-3 py-2 rounded-md border hover:bg-white"
            onClick={async () => { await mockLogout(); window.location.reload(); }}
          >ログアウト</button>
        </div>
      </header>

      <main className="max-w-4xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Left column: Surveys & History */}
        <section className="md:col-span-2 bg-white rounded-2xl p-4 shadow-sm">
          <h2 className="text-xl font-semibold mb-3">アンケート回答履歴</h2>
          <div className="space-y-3">
            {surveys.length === 0 && <div className="text-slate-500">まだ回答がありません</div>}
            {surveys.map(s => (
              <div key={s.id} className="p-3 border rounded-lg flex justify-between items-center">
                <div>
                  <div className="font-medium">{s.title}</div>
                  <div className="text-sm text-slate-500">回答日: {s.answeredAt ?? '未回答'}</div>
                </div>
                <div className="text-right">
                  <div className="text-sm text-slate-600">獲得ポイント</div>
                  <div className="font-bold">+{s.points} pt</div>
                </div>
              </div>
            ))}
          </div>

          <div className="mt-6">
            <h3 className="font-semibold mb-2">最近のお知らせ</h3>
            <ul className="space-y-2">
              {notifications.length === 0 && <li className="text-slate-500">お知らせはありません</li>}
              {notifications.map(n => (
                <li key={n.id} className="p-2 border rounded-md">{n.message} <div className="text-xs text-slate-400">{n.date}</div></li>
              ))}
            </ul>
          </div>
        </section>

        {/* Right column: Exchange & Actions */}
        <aside className="bg-white rounded-2xl p-4 shadow-sm">
          <h2 className="text-lg font-semibold mb-3">ポイントで交換</h2>
          <div className="space-y-3">
            {exchanges.length === 0 && <div className="text-slate-500">交換できる景品がありません</div>}
            {exchanges.map(item => (
              <div key={item.id} className="flex items-center justify-between p-3 border rounded-md">
                <div>
                  <div className="font-medium">{item.title}</div>
                  <div className="text-sm text-slate-500">必要ポイント: {item.cost} pt</div>
                </div>
                <div>
                  <button
                    disabled={user?.points < item.cost}
                    onClick={async () => {
                      if (confirm(`『${item.title}』を${item.cost}ポイントで交換しますか？`)) {
                        try {
                          await mockDoExchange(item.id);
                          alert('交換申請を受け付けました');
                          // refresh
                          const u = await mockFetchUser();
                          setUser(u);
                        } catch (e) { alert('交換に失敗しました'); }
                      }
                    }}
                    className="px-3 py-1 rounded-md border disabled:opacity-50"
                  >交換</button>
                </div>
              </div>
            ))}
          </div>

          <div className="mt-6">
            <h3 className="font-semibold">クイック操作</h3>
            <div className="flex flex-col gap-2 mt-2">
              <a href="/surveys" className="block text-center px-3 py-2 border rounded-md">アンケート一覧へ</a>
              <a href="/opinions" className="block text-center px-3 py-2 border rounded-md">意見箱</a>
              <a href="/history" className="block text-center px-3 py-2 border rounded-md">ポイント履歴</a>
            </div>
          </div>
        </aside>
      </main>

      <footer className="max-w-4xl mx-auto mt-8 text-center text-slate-500">© プロジェクト: 情報格差の解消</footer>
    </div>
  );
}

/* -------------------- Mock API (replace with real fetch calls) -------------------- */

async function mockFetchUser() {
  // simulate network delay
  await wait(200);
  return { id: 'c1234567890', name: '金道敏樹', points: 420 };
}

async function mockFetchSurveys() {
  await wait(150);
  return [
    { id: 's1', title: '使いやすさに関するアンケート', answeredAt: '2025-10-21', points: 20 },
    { id: 's2', title: 'サービス改善の提案', answeredAt: '2025-10-15', points: 30 },
  ];
}

async function mockFetchExchanges() {
  await wait(120);
  return [
    { id: 'e1', title: '図書カード 500円分', cost: 300 },
    { id: 'e2', title: 'オリジナルグッズ', cost: 500 },
  ];
}

async function mockFetchNotifications() {
  await wait(80);
  return [
    { id: 'n1', message: '新しいアンケートが追加されました。', date: '2025-10-20' },
  ];
}

async function mockDoExchange(exchangeId) {
  await wait(200);
  // deduct points in a real API
  return { success: true };
}

async function mockLogout() {
  await wait(100);
  return true;
}

function wait(ms) { return new Promise(r => setTimeout(r, ms)); }
